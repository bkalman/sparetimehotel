<?php
use app\model\Rooms;
use app\model\Menu;
use app\model\Allergens;

$rooms = Rooms::findAll();
$menu = Menu::findAll();
$allergens = Allergens::findAll();
/** @var Rooms[] $rooms */
/** @var Menu[] $menu */
/** @var Allergens[] $allergens */

if(!empty($_SESSION['reservation'])): ?>

    <section id="container">
        <div class="container">
            <div class="row" id="reservation_hr">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=date">Szoba</a></div>
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=room">Menü</a></div>
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=menu" class="text-success">Személyes adatok</a></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h1>Szoba kiválasztása</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <form method="post" id="done_form">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="last_name">Vezetéknév</label>
                                <input type="text" name="reservation[last_name]" id="last_name" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="col-md-6">
                                <label for="first_name">Keresztnév</label>
                                <input type="text" name="reservation[first_name]" id="first_name" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="col-12">
                                <label for="email">E-mail</label>
                                <input type="email" name="reservation[email]" id="email" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="col-12">
                                <label for="phone_number">Telefonszám</label>
                                <input type="tel" name="reservation[phone_number]" id="phone_number" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="col-12">
                                <h1 class="my-5">Összegzés:</h1>
                                <h3 class="mt-5">Időpont és fő</h3>
                                Bejelentkezés: <?= $_SESSION['reservation']['start_date'] ?><br>
                                Kijelentkezés: <?= $_SESSION['reservation']['end_date'] ?><br>
                                <?= $_SESSION['reservation']['adult'] ?> felnőtt<br>
                                <?= $_SESSION['reservation']['child'] ?> gyermek<br>

                                <h3 class="mt-5">Szoba:</h3>
                                <ul class="ml-5">
                                    <li><?=Rooms::findOneById($_SESSION['reservation']['room_id'])->getStorey()?>. emelet</li>
                                    <li><?=Rooms::findOneById($_SESSION['reservation']['room_id'])->getBed()?> darab kétszemélyes ágy</li>
                                    <li><?=Rooms::findOneById($_SESSION['reservation']['room_id'])->getExtras()?></li>
                                    <li><?=Rooms::findOneById($_SESSION['reservation']['room_id'])->getPrice()?> Ft / fő / éj</li>
                                </ul>

                                <h3 class="mt-5">Rendelések:</h3>
                                <table class="table">
                                    <thead>
                                        <th>Dátum</th>
                                        <th>Reggeli</th>
                                        <th>Ebéd</th>
                                        <th>Vacsira</th>
                                        <th>Ár</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($_SESSION['reservation']['menu'])) :
                                        foreach ($_SESSION['reservation']['menu'] as $m): ?>
                                                <tr>
                                                    <td><?=$m['date']?></td>
                                                    <td><?=$m['breakfast']?></td>
                                                    <td><?=$m['lunch']?></td>
                                                    <td><?=$m['dinner']?></td>
                                                    <td><?=$m['price']?> Ft</td>
                                                </tr>
                                        <?php endforeach; elseif(empty($_SESSION['reservation']['menu'])) : echo '<tr><td colspan="5" class="text-center">-üres-</td></tr>'; endif; ?>
                                    </tbody>
                                </table>
                                <br>
                            </div>
                            <div class="col-12">
                                <input type="submit" value="Foglalás befejezése" class="btn btn-success mt-4 w-100">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php else: header('location: index.php'); endif; ?>