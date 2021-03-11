<?php
use app\model\Jobs;
use app\model\Guests;

$guests = Guests::findAll();

if(Jobs::currentUserCan('function.guests')): ?>
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
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($guests as $k => $v): ?>
                                <tr>
                                    <td scope="row"><?=$v->getLastName()?> <?=$v->getFirstName()?></td>
                                    <td><?=$v->getEmail()?></td>
                                    <td><?=$v->getPhoneNumber()?></td>
                                </tr
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
<?php endif; ?>