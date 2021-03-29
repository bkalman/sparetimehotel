<?php
use app\model\Jobs;
use app\model\ErrorReports;
use app\model\Rooms;

$reports = ErrorReports::findAll();
$rooms = Rooms::findAll();
$storeys = Rooms::findAllStorey();

$types = ['szoba' => 'szoba','folyoso' => 'folyosó','hall' => 'hall','iroda' => 'iroda','konyha' => 'konyha','etkezo' => 'étkező','kert' => 'kert'];
$status = ['tonkrement' => 'tönkrement','meghibasodott' => 'meghibásodott','egyeb' => 'egyéb'];

/** @var Rooms[] $rooms */
/** @var Rooms[] $storey */
/** @var ErrorReports[] $reports */
if(Jobs::currentUserCan('function.report')): ?>
    <section id="container">
        <div class="container">
            <?php if(!empty($_GET['delete']) && $_GET['delete'] == 'true'): ?>
                <div class="row">
                    <div class="col-12">
                        <h3 style="color:green;">Sikeres befejezés!</h3>
                    </div>
                </div>
            <?php elseif(!empty($_GET['delete']) && $_GET['delete'] == 'false'): ?>
                <div class="row">
                    <div class="col-12">
                        <h3 style="color:red;">Sikertelen befejezés! Próbálja meg újból!</h3>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12">
                    <h1>Hibabejelentések</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Emelet</th>
                                <th scope="col"">Hely</th>
                                <th scope="col"">Szobaszám</th>
                                <th scope="col">Státusz</th>
                                <th scope="col">Üzenet</th>
                                <th scope="col" style="width: 40px">Szerkesztés</th>
                                <th scope="col" style="width: 40px">Kész</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div id="errorReportsModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="errorReports_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hibabejelentés</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <label for="room_id">Szobaszám</label>
                        <select name="errorReports[room_id]" id="room_id" class="form-control">
                            <option>-</option>
                            <?php foreach ($rooms as $k => $v): ?>
                                <option value="<?=$v->getRoomId()?>"><?=$v->getRoomId()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="storey">Emelet<span style="color:red">*</span></label>
                        <select name="errorReports[storey]" id="storey" class="form-control">
                            <option value="0">0</option>
                            <?php foreach ($storeys as $v): ?>
                                <option value="<?=$v->getStorey()?>"><?=$v->getStorey()?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Státusz<span style="color:red">*</span></label>
                        <select name="errorReports[status]" id="status" class="form-control">
                            <?php foreach ($status as $k =>$v): ?>
                                <option value="<?=$k?>"><?=$v?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="report">Üzenet</label>
                        <textarea name="errorReports[report]" id="report" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="errorReports[report_id]" id="report_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Hozzáad">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>