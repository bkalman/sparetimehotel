<?php
use app\model\Jobs;
use app\model\Guests;
use app\model\RoomBooking;
use app\model\Menu;
use app\model\Allergens;
use app\model\Recommendation;

$guests = Guests::findAll();
$reservations = RoomBooking::findAll();
$menu = Menu::findAll();
$allergens = Allergens::findAll();
$recommendation = Recommendation::findAll();
/** @var RoomBooking[] $reservations */
/** @var Menu[] $menu */
/** @var Allergens[] $allergens */
/** @var Recommendation[] $recommendation */
if(Jobs::currentUserCan('function.reservations')): ?>
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
                            <th scope="col">Sorszám</th>
                            <th scope="col">Név</th>
                            <th scope="col">Név</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Telefonszám</th>
                            <th scope="col">Fő</th>
                            <th scope="col">Szobaszám</th>
                            <th scope="col">Bejelentkezés</th>
                            <th scope="col">Kijelentkezés</th>
                            <th scope="col" style="width: 100px">Szerkesztés</th>
                            <th scope="col" style="width: 100px">Check In</th>
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
                        <h5 class="modal-title">Étel hozzáadás</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="last_name">Név</label>
                                <input type="text" name="reservations[last_name]" id="last_name" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-6">
                                <label for="first_name">Név</label>
                                <input type="text" name="reservations[first_name]" id="first_name" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="text" name="reservations[email]" id="email" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Telefonszám</label>
                            <input type="number" name="reservations[phone_number]" id="phone_number" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-4">
                                <label for="adult">Felnőtt</label>
                                <input type="number" name="reservations[adult]" id="adult" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-4">
                                <label for="child">Gyermek</label>
                                <input type="number" name="reservations[child]" id="child" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-4">
                                <label for="room_id">Szobaszám</label>
                                <input type="number" name="reservations[room_id]" id="room_id" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="start_date">Bejelentkezés</label>
                                <input type="date" name="reservations[start_date]" id="start_date" class="form-control">
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-6">
                                <label for="end_date">Kijelentkezés</label>
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
<?php endif; ?>
