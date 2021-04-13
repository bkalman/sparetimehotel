<?php
use app\model\Jobs;
use app\model\ErrorReports;
use app\model\Rooms;
use app\model\Employees;

$reports = ErrorReports::findAll();
$rooms = Rooms::findAll();
$storeys = Rooms::findAllStorey();
$employees = Employees::findAll();

$types = ['szoba' => 'szoba','folyoso' => 'folyosó','hall' => 'hall','iroda' => 'iroda','konyha' => 'konyha','etkezo' => 'étkező','kert' => 'kert'];
$status = ['tönkrement','meghibásodott','egyéb'];

/** @var Rooms[] $rooms */
/** @var Rooms[] $storey */
/** @var ErrorReports[] $reports */
/** @var Employees[] $employees */
if(Jobs::currentUserCan('function.report')): ?>
    <section id="container">
        <div class="container">
            <div class="container box">
                <div class="table-responsive">
                    <br>
                    <div align="right">
                        <button type="button" id="add_button" data-toggle="modal" data-target="#errorReportsModal" class="btn btn-info btn-lg">Felvétel</button>
                    </div>
                    <br><br>
                    <table id="errorReports_data" class="table">
                        <thead>
                        <tr>
                            <th scope="col">Szoba</th>
                            <th scope="col"">Típus</th>
                            <th scope="col"">Emelet</th>
                            <th scope="col">Státusz</th>
                            <th scope="col">Üzenet</th>
                            <th scope="col">Bejelentés</th>
                            <th scope="col" style="width: 40px">Naplózás</th>
                            <th scope="col" style="width: 40px">Szerkesztés</th>
                            <th scope="col" style="width: 40px">Befejezve</th>
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
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="room_id">Szoba</label>
                                <select name="errorReports[room_id]" id="room_id" class="form-control">
                                    <option default></option>
                                    <?php foreach ($rooms as $k => $v):
                                        $numbers = [2,6,11,16,21,26,30];
                                        for ($n = 0; $n < count($numbers); $n++) {
                                            if ($v->getRoomId() == $numbers[$n]) {
                                                echo '<option disabled>---- '.($n+1).'. emelet ---- </option>';
                                                echo '<option value="corridor">folyosó</option>';
                                            }
                                        }
                                        ?>
                                        <option value="<?=$v->getRoomId()?>"><?= is_null($v->getPrice()) ? $v->getType() :$v->getRoomId().'. '.$v->getType()?></option>

                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="errorReports[place]" id="place">
                                <input type="hidden" name="errorReports[storey]" id="storey">
                            </div>
                            <div class="form-group col-md-8">
                                <p style="font-size: 15px;text-align: justify;margin:0;">*Egyéb helység esetén hagyja üresen, és fejtse ki a megjegyzésben!</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="labelUp">Státusz</label>
                            <select name="errorReports[status]" id="status" class="form-control">
                                <?php foreach ($status as $v): ?>
                                    <option value="<?=$v?>"><?=$v?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="report">Megjegyzés</label>
                            <textarea name="errorReports[report]" id="report" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="errorReports[report_id]" id="report_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Bejelentés">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div id="diaryModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="diary_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Naplózás</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="employee_id" class="labelUp">Karbantartó</label>
                            <select name="diary[employee_id]" id="employee_id" class="form-control">
                                <?php foreach ($employees as $k => $v):
                                    if(Jobs::findOneById($v->getJobId())->getTitle() == 'karbantartó'): ?>
                                    <option value="<?=$v->getEmployeeId()?>"><?=$v->getLastName().' '.$v->getFirstName()?></option>
                                <?php endif; endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="started" class="labelUp">Bejelentés</label>
                                <input type="date" name="diary[started]" id="started" class="form-control" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="finished" class="labelUp">Befejezés</label>
                                <input type="date" name="diary[finished]" id="finished" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="comment">Megjegyzés</label>
                            <textarea name="diary[comment]" id="comment" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <h6 class="mt-2 mb-4">Ha voltak számlaképes költségei a javításnak, töltse fel azokat szkennelve/fotózva, az árat külön megjelölve a rublikákban.</h6>
                        <div id="bills"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="text" name="diary[price_all]" id="price_all" class="form-control" style="border:0;" disabled>
                        <input type="hidden" name="diary[report_id]" id="report_id_diary">
                        <input type="hidden" name="operation_diary" id="operation_diary">
                        <input type="submit" name="action_diary" id="action_diary" class="btn btn-success" value="Hozzáad">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>