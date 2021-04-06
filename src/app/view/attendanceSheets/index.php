<?php
use app\model\Jobs;
use app\model\AttendanceSheets;
use app\model\Employees;

$sort = !empty($_GET['sort'])? $_GET['sort'] : 'asc';
$id = !empty($_GET['id'])? $_GET['id'] : $_SESSION['employee_id'];
$date = !empty($_GET['date'])? $_GET['date'] : date('Ym');
$year = substr($date,0,4);
$month = substr($date,4);
/** @var AttendanceSheets[] $attendanceSheets */
/** @var Employees[] $employees */
$attendanceSheets = Jobs::currentUserCan('function.employee')? AttendanceSheets::findOneById($id,$sort,$year,$month) : AttendanceSheets::findOneById($_SESSION['employee_id'],$sort,$year,$month);
$employees = Employees::findAll();

?>
<section id="container">
    <div class="container">
        <div class="container box">
            <div class="table-responsive">
                <?php if(Jobs::currentUserCan('function.employee')): ?>
                <div align="right">
                    <button type="button" id="add_button" data-toggle="modal" data-target="#attendace_sheetsModal" class="btn btn-info btn-lg">Hónap hozzáadása</button>
                </div>
                <?php endif; ?>
                <table id="attendace_sheets_data" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col"">Dátum</th>
                            <th scope="col"">Kezdés</th>
                            <th scope="col">Befejezés</th>
                            <th scope="col">Ledolgozott órák</th>
                            <th scope="col">Szünet</th>
                            <th scope="col">Állapot</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<?php if(Jobs::currentUserCan('function.employee')): ?>
    <div id="attendace_sheetsModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="attendace_sheets_form" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hónap hozzáadás</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="employee_id" class="labelUp">Munkavállaló</label>
                                <select name="attendace_sheets[employee_id]" id="employee_id" class="form-control">
                                    <?php foreach ($employees as $e): ?>
                                        <option value="<?=$e->getEmployeeId()?>"><?=$e->getLastName().' '.$e->getFirstName()?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Nincsen kitöltve!</div>
                            </div>
                            <div class="form-group col-md-6">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="attendace_sheets[room_booking_id]" id="room_booking_id">
                        <input type="hidden" name="operation" id="operation">
                        <input type="submit" name="action" id="action" class="btn btn-success" value="Hozzáad">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>