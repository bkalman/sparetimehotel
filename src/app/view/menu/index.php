<?php
use app\model\Jobs;
use app\model\Menu;
use app\model\Allergens;
use app\model\Recommendation;

$menu = Menu::findAll();
$allergens = Allergens::findAll();
$recommendation = Recommendation::findAll();
/** @var Menu[] $menu */
/** @var Allergens[] $allergens */
/** @var Recommendation[] $recommendation */
?>
    <section id="container">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <h3 id="restaurant_orders"><a href="index.php?controller=view&action=orders">Rendelések</a></h3>
                </div>
                <div class="col-6">
                    <h3 id="restaurant_menu"><a href="index.php?controller=view&action=menu" class="text-underline">Menü</a></h3>
                </div>
            </div>
            <div class="container box">
                <div class="table-responsive">
                    <?php if(Jobs::currentUserCan('function.menu')): ?>
                        <div align="right">
                            <button type="button" id="add_button" data-toggle="modal" data-target="#menuModal" class="btn btn-info btn-lg">Felvétel</button>
                        </div><br><br>
                    <?php endif; ?>
                    <table id="menu_data" class="table">
                        <thead>
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col"">Allergének</th>
                            <th scope="col" style="width: 50px;">Ár</th>
                            <th scope="col" style="width: 50px;">Érvényesség</th>
                            <th scope="col">Ajánlat</th>
                            <?php if(Jobs::currentUserCan('function.menu')): ?>
                                <th scope="col" style="width: 70px">Szerkesztés</th>
                                <th scope="col" style="width: 70px">Törlés</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php if(Jobs::currentUserCan('function.menu')): ?>
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
                            <label for="name">Név</label>
                            <input type="text" name="menu[name]" id="name" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h5>Allergének</h5>
                            </div>
                            <?php foreach ($allergens as $allergen): ?>
                                <div class="form-check offset-sm-1 col-sm-5 my-0">
                                    <input class="form-check-input" type="checkbox" name="allergens[<?=$allergen->getAllergenId()?>]" id="allergen<?=$allergen->getAllergenId()?>" value="<?=$allergen->getAllergenId()?>">
                                    <label class="form-check-label" for="allergen<?=$allergen->getAllergenId()?>"><?=$allergen->getName()?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-row mt-3">
                            <div class="form-group col-md-4">
                                <label for="price">Ár</label>
                                <input type="number" name="menu[price]" id="price" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="recommendation" class="labelUp">Ajánlat</label>
                                <select name="menu[recommendation]" id="recommendation" class="form-control">
                                    <option default></option>
                                    <?php foreach ($recommendation as $r): ?>
                                        <option value="<?=$r->getRecommendationId()?>"><?=$r->getTitle()?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-check col-md-4 text-center">
                                <input type="checkbox" name="menu[current]" id="current" class="form-check-input">
                                <label for="current" class="form-check-label">érvényes</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="menu[menu_id]" id="menu_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Hozzáad">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>