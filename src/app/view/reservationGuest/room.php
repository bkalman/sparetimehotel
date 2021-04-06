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

$sdate = date_create($_SESSION['reservation']['start_date']);
$edate = date_create($_SESSION['reservation']['end_date']);

$day = date_diff($sdate,$edate)->format('%a');
$adult = $_SESSION['reservation']['adult'];

if(!empty($_SESSION['reservation'])): ?>

    <section id="container">
        <div class="container">
            <div class="row" id="reservation_hr">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=date" class="text-success">Szoba</a></div>
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=room">Menü</a></div>
                        <div class="col-md-4"><a href="index.php?controller=reservationGuest&action=menu">Személyes adatok</a></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h1>Szoba kiválasztása</h1>
                    <p>Az árba bele van számítva a(z) <?=$adult?> felnőtt és a(z) <?=$day?> éjszaka.</p>
                </div>
            </div>
            <div class="row">
                <?php foreach ($rooms as $r): if ($r->getType() == 'szoba'): ?>
                    <div class="col-12 col-md-6 col-lg-4 my-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?=$r->getStorey()?>. emelet</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><?=$r->getBed()?> darab kétszemélyes ágy</li>
                                <li class="list-group-item">extrák: <?=$r->getExtras()?></li>
                            </ul>
                            <div class="card-body">
                                <p><?=($r->getPrice()*$day*$adult)?> Ft</p>
                                <form action="index.php?controller=reservationGuest&action=room" method="post">
                                    <input type="hidden" name="reservation[room_id]" id="room_id" value="<?=$r->getRoomId()?>">
                                    <input type="submit" value="Választ és tovább" class="btn btn-primary">
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
    </section>

<?php else: header('location: index.php'); endif; ?>