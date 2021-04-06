<?php
use app\model\Rooms;
use app\model\Menu;
use app\model\Allergens;
use app\model\Recommendation;

$rooms = Rooms::findAll();
$menu = Menu::findAll();
$allergens = Allergens::findAll();
$recommendation = Recommendation::findAll();
/** @var Rooms[] $rooms */
/** @var Menu[] $menu */
/** @var Allergens[] $allergens */
/** @var Recommendation[] $recommendation */
if(!empty($_SESSION['reservation']['room_id'])): ?>

    <section id="container">
        <div class="container">
            <div class="row" id="reservation_hr">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=date">Szoba</a></div>
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=room" class="text-success">Menü</a></div>
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=menu">Személyes adatok</a></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p>Nem köteles azonnal megrendelni az ételt, a bejelentkezéskor is megteheti.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="container box">
                        <div class="table-responsive">
                            <div align="right">
                                <button type="button" id="add_button" data-toggle="modal" data-target="#menuModal" class="btn btn-info btn-lg">Rendelés</button>
                            </div>
                            <div>
                                <h3>Eddigi rendelések:</h3>
                                <div id="reservation_orders" class="mx-5">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Dátum</th>
                                                <th>Reggeli</th>
                                                <th>Ebéd</th>
                                                <th>Vacsora</th>
                                                <th>Ár</th>
                                                <th>Törlés</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <a href="index.php?controller=reservationGuest&action=menu" class="btn btn-primary m-5">Tovább</a>
                            <hr class="my-5">
                            <h3>Menü:</h3>
                            <table id="menu_data" class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Név</th>
                                    <th scope="col">Allergének</th>
                                    <th scope="col">Ár</th>
                                    <th scope="col">Ajánlat</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="menuModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="menu_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Étel hozzáadás</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="labelUp">Dátum</label>
                            <input type="date" name="reservation[menu][date]" id="name" class="form-control" min="<?=$_SESSION['reservation']['start_date']?>" max="<?=$_SESSION['reservation']['end_date']?>">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group">
                            <label for="breakfast" class="labelUp">Reggeli</label>
                            <select name="reservation[menu][breakfast]" id="breakfast" class="form-control">
                                <option></option>
                                <?php foreach ($menu as $m): if($m->getMenuId() != 0): ?>
                                    <option value="<?=$m->getMenuId()?>"><?=$m->getName()?></option>
                                <?php endif; endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group">
                            <label for="lunch" class="labelUp">Ebéd</label>
                            <select name="reservation[menu][lunch]" id="lunch" class="form-control">
                                <option></option>
                                <?php foreach ($menu as $m): if($m->getMenuId() != 0): ?>
                                    <option value="<?=$m->getMenuId()?>"><?=$m->getName()?></option>
                                <?php endif; endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group">
                            <label for="dinner" class="labelUp">Vacsora</label>
                            <select name="reservation[menu][dinner]" id="dinner" class="form-control">
                                <option></option>
                                <?php foreach ($menu as $m): if($m->getMenuId() != 0) :?>
                                    <option value="<?=$m->getMenuId()?>"><?=$m->getName()?></option>
                                <?php endif; endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Hozzáad">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php else: header('location: index.php'); endif; ?>