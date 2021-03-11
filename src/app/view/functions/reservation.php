<?php
use app\model\Jobs;
use app\model\Guests;
use app\model\RoomBooking;

$guests = Guests::findAll();
$reservations = RoomBooking::findAll();

/** @var RoomBooking[] $reservations */
if(Jobs::currentUserCan('function.reservation')): ?>
        <section id="container">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>Foglalások</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">
                                    Sorszám
                                </th>
                                <th scope="col">
                                    Név
                                </th>
                                <th scope="col">
                                    E-mail
                                </th>
                                <th scope="col">
                                    Telefonszám
                                </th>
                                <th scope="col">
                                    Fő
                                </th>
                                <th scope="col">
                                    Szobaszám
                                </th>
                                <th scope="col">
                                    Bejelentkezés
                                </th>
                                <th scope="col">
                                    Kijelentkezés
                                </th>
                                <th scope="col" style="width: 100px">
                                    Check In
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($reservations as $k => $v): ?>
                                <tr>
                                    <form action="index.php?controller=roomBooking&action=checkIn" method="post">
                                        <input type="hidden" name="roomBookingId" value="<?=$v->getRoomBookingId()?>">
                                        <td><?=$v->getRoomBookingId()?></td>
                                        <td><?=Guests::findOneById($v->getGuestId())->getLastName()?> <?=Guests::findOneById($v->getGuestId())->getFirstName()?></td>
                                        <td><?=Guests::findOneById($v->getGuestId())->getEmail()?></td>
                                        <td><?=Guests::findOneById($v->getGuestId())->getPhoneNumber()?></td>
                                        <td><?=$v->getAdult()?> felnőtt, <?=$v->getChild()?> gyerek</td>
                                        <td><?=$v->getRoomId()?></td>
                                        <td><?=$v->getStartDate()?></td>
                                        <td><?=$v->getEndDate()?></td>
                                        <td>
                                            <?php if(date('Y-m-d') <= date('Y-m-d',strtotime($v->getStartDate()))): ?>
                                                <?php if($v->getCheckIn() == 1): ?>
                                                <button type="submit" class="btn btn-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                                        <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                                    </svg>
                                                </button>
                                                <?php elseif($v->getCheckIn() == 0):?>
                                                    <button type="submit" class="btn btn-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                                        </svg>
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </form>
                                </tr
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
<?php endif; ?>