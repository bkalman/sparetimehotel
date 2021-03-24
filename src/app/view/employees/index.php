<?php
use app\model\Jobs;
$jobs = Jobs::findAll();
/** @var Jobs[] $jobs */

if(Jobs::currentUserCan('function.employee')): ?>
<section id="container">
    <div class="container box">
        <h1>Munkavállalók</h1>
        <br>
        <div class="table-responsive">
            <br>
            <div align="right">
                <button type="button" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-info btn-lg">Felvétel</button>
            </div>
            <br><br>
            <table id="user_data" class="table">
                <thead>
                <tr>
                    <th scope="col" style="width:200px">Név</th>
                    <th scope="col"">Munkakör</th>
                    <th scope="col"">Telefonszám</th>
                    <th scope="col">E-mail</th>
                    <th scope="col" style="width: 400px">Lakcím</th>
                    <th scope="col">Szerkesztés</th>
                    <th scope="col">Törlés</th>
                </tr>
                </thead>
            </table>

        </div>
    </div>
</section>
<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Munkavállaló felvétel</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-6 form-group">
                            <label for="last_name">Vezetéknév</label>
                            <input type="text" name="employee[last_name]" id="last_name" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="col-6 form-group">
                            <label for="first_name">Keresztnév</label>
                            <input type="text" name="employee[first_name]" id="first_name" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="employee[email]" id="email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        <div class="invalid-feedback">Nincsen kitöltve!</div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Telefonszám</label>
                        <input type="tel" name="employee[phone_number]" id="phone_number" class="form-control" pattern="(06)?[0-9]{1,2}[0-9]{7}">
                        <div class="invalid-feedback">Nincsen kitöltve!</div>
                    </div>
                    <div class="form-group">
                        <label for="job_id" class="labelUp">Munkakör</label>
                        <select name="employee[job_id]" id="job_id" class="form-control">
                            <option value="">-</option>
                            <?php foreach($jobs as $job): ?>
                                <option value="<?=$job->getJobId()?>"><?=$job->getTitle()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-5">
                            <label for="zip">Irányítószám</label>
                            <input type="text" name="employee[zip]" id="zip" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group col-7">
                            <label for="city">Város</label>
                            <input type="text" name="employee[city]" id="city" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="street_address">Közterület</label>
                        <input type="text" name="employee[street_address]" id="street_address" class="form-control">
                        <div class="invalid-feedback">Nincsen kitöltve!</div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="house_number">Házszám</label>
                            <input type="number" name="employee[house_number]" id="house_number" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group col-6">
                            <label for="floor_door">Emelet/Ajtó</label>
                            <input type="text" name="employee[floor_door]" id="floor_door" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-6 form-group">
                        <label for="password">Jelszó</label>
                        <input type="password" name="employee[password]" id="password" class="form-control">
                        <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="col-6 form-group">
                            <label for="password_repeat">Jelszó újra</label>
                            <input type="password" name="employee[password_repeat]" id="password_repeat" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="employee[employee_id]" id="employee_id">
                    <input type="hidden" name="operation" id="operation">
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Felvétel">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php else: header('location: index.php'); endif; ?>