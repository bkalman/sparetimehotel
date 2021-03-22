<?php
use app\model\Jobs;
use app\model\Orders;
use app\model\RoomBooking;
use app\model\Guests;
use app\model\Menu;

$orders = Orders::findAll();
/** @var Orders[] $orders */
if(Jobs::currentUserCan('function.restaurant')): ?>
    <section id="container">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1>Étterem</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">
                                Név
                            </th>
                            <th scope="col"">
                                Dátum
                            </th>
                            <th scope="col"">
                                Reggeli
                            </th>
                            <th scope="col">
                                Ebéd
                            </th>
                            <th scope="col">
                                Vacsora
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($orders as $k => $v):
                            if(date('Y-m-d') <= date('Y-m-d',strtotime($v->getDate()))): ?>
                            <tr>
                                <td scope="row">
                                    <?=Guests::findOneById(RoomBooking::findOneById($v->getRoomBookingId())->getGuestId())->getLastName()?>
                                    <?=Guests::findOneById(RoomBooking::findOneById($v->getRoomBookingId())->getGuestId())->getFirstName()?>
                                </td>
                                <td><?=$v->getDate()?></td>
                                <td><?= $v->getBreakfast() == 0 ? '' : Menu::findOneById($v->getBreakfast())->getName() ?></td>
                                <td><?= $v->getLunch() == 0 ? '' : Menu::findOneById($v->getLunch())->getName() ?></td>
                                <td><?= $v->getDinner() == 0 ? ''  : Menu::findOneById($v->getDinner())->getName()?></td>
                            </tr
                        <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>