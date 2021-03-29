<?php
use app\model\Jobs;
use app\model\Guests;
use app\model\RoomBooking;
use app\model\Menu;
use app\model\Allergens;
use app\model\Recommendation;
use app\model\Rooms;

$guests = Guests::findAll();
$reservations = RoomBooking::findAll();
$menu = Menu::findAll();
$allergens = Allergens::findAll();
$recommendation = Recommendation::findAll();
$rooms = Rooms::findAll();
/** @var Guests[] $guests */
/** @var RoomBooking[] $reservations */
/** @var Menu[] $menu */
/** @var Allergens[] $allergens */
/** @var Recommendation[] $recommendation */
/** @var Rooms[] $rooms */
if(Jobs::currentUserCan('function.reservation')): ?>
    <section id="container">
        <div class="container">
            <div class="container box">
                <div class="table-responsive">
                    <br>
                    <div align="right">
                        <button type="button" id="add_button" data-toggle="modal" data-target="#reservationsModal" class="btn btn-info btn-lg">Felvétel</button>
                    </div>
                    <br><br>
                    <table id="reservations_data" class="table">
                        <thead>
                        <tr>
                            <th scope="col">S.</th>
                            <th scope="col">Név</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Telszám</th>
                            <th scope="col">Fő</th>
                            <th scope="col">Szoba</th>
                            <th scope="col" style="width: 100px">Bejelentkezés</th>
                            <th scope="col" style="width: 100px">Kijelentkezés</th>
                            <th scope="col" style="width: 40px">Szerkesztés</th>
                            <th scope="col" style="width: 40px">Check</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div id="reservationsModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="reservations_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Foglalás hozzáadás</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="guest_id" class="labelUp">Név</label>
                                <select name="reservations[guest_id]" id="guest_id" class="form-control">

                                </select>
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-md-6" id="newUserButton">
                                <input type="button" class="btn btn-primary w-100" id="newUser" value="Új vendég">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="adult" class="labelUp">Felnőtt</label>
                                <input type="number" name="reservations[adult]" id="adult" class="form-control" value="1" min="0">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-4">
                                <label for="child" class="labelUp">Gyermek</label>
                                <input type="number" name="reservations[child]" id="child" class="form-control" value="0" min="0">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-4">
                                <label for="room_id" class="labelUp">Szoba</label>
                                <select name="reservations[room_id]" id="room_id" class="form-control">
                                    <option default></option>
                                    <?php foreach ($rooms as $r): ?>
                                        <?php
                                        $numbers = [1,6,11,16,21];
                                        for ($n = 0; $n < count($numbers); $n++) {
                                            if ($r->getRoomId() == $numbers[$n]) echo '<option disabled>----- '.($n+1).'. emelet -----</option>';
                                        }

                                        if(!is_null($r->getPrice())): ?>
                                        <option value="<?=$r->getRoomId()?>"><?=$r->getRoomId().' / '.$r->getBed().' ágy / '.$r->getExtras().' / '.$r->getPrice()?>Ft</option>
                                    <?php endif; endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_date" class="labelUp">Bejelentkezés</label>
                                <input type="date" name="reservations[start_date]" id="start_date" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date" class="labelUp">Kijelentkezés</label>
                                <input type="date" name="reservations[end_date]" id="end_date" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="reservations[room_booking_id]" id="room_booking_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Hozzáad">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="guestModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="guest_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Vendég regisztrálása</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="last_name">Vezetéknév</label>
                                <input type="text" name="guest[last_name]" id="last_name" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="first_name">Keresztnév</label>
                                <input type="text" name="guest[first_name]" id="first_name" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" name="guest[email]" id="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Telefonszám</label>
                            <input type="number" name="guest[phone_number]" id="phone_number" class="form-control" pattern="(06)?[0-9]{1,2}[0-9]{7}">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="guest[guest_id]" id="guest_id">
                        <input type="hidden" name="operationUser" id="operationUser">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Regisztrál">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
