<?php
use app\model\Jobs;
use app\model\Orders;
use app\model\Guests;
use app\model\Menu;

$orders = Orders::findAll();
$guests = Guests::findAll();
$menu = Menu::findAll();
/** @var Orders[] $orders */
/** @var Guests[] $guests */
/** @var Menu[] $menu */
if(Jobs::currentUserCan('function.restaurant')): ?>
    <section id="container">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h3 id="restaurant_orders"><a href="index.php?controller=hyperlink&action=orders">Rendelések</a></h3>
                </div>
                <div class="col-6">
                    <h3 id="restaurant_menu"><a href="index.php?controller=hyperlink&action=menu">Menü</a></h3>
                </div>
            </div>
            <div class="container box">
                <div class="table-responsive">
                    <br>
                    <div align="right">
                        <button type="button" id="add_button" data-toggle="modal" data-target="#orderModal" class="btn btn-info btn-lg">Felvétel</button>
                    </div>
                    <br><br>
                    <table id="order_data" class="table">
                        <thead>
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col"">Dátum</th>
                            <th scope="col"">Reggeli</th>
                            <th scope="col">Ebéd</th>
                            <th scope="col">Vacsora</th>
                            <th scope="col">Szerkesztés</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div id="orderModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="order_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rendelés felvétel</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name" class="labelUp">Név</label>
                            <select name="order[guest_id]" id="name" class="form-control">
                                <option default></option>
                                <?php foreach($guests as $guest): ?>
                                    <option value="<?=$guest->getGuestId()?>"><?=$guest->getLastName()?> <?=$guest->getFirstName()?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="labelUp">Dátum</label>
                            <input type="date" name="order[date]" id="date" value="<?=date('Y-m-d')?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email" class="labelUp">Reggeli</label>
                            <select name="order[breakfast]" id="breakfast" class="form-control">
                                <option default></option>
                                <?php foreach($menu as $food): ?>
                                    <option value="<?=$food->getMenuId()?>"><?=$food->getName()?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone_number" class="labelUp">Ebéd</label>
                            <select name="order[lunch]" id="lunch" class="form-control">
                                <option default></option>
                                <?php foreach($menu as $food): ?>
                                    <option value="<?=$food->getMenuId()?>"><?=$food->getName()?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="zip" class="labelUp">Vacsora</label>
                            <select name="order[dinner]" id="dinner" class="form-control">
                                <option default></option>
                                <?php foreach($menu as $food): ?>
                                    <option value="<?=$food->getMenuId()?>"><?=$food->getName()?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="order[order_id]" id="order_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Felvétel">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>