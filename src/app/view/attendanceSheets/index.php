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
                    <button type="button" id="add_month" class="btn btn-info btn-lg">Jelenlegi hónap hozzáadása mindenkinek</button>
                </div>
                <?php endif; ?>
                <table id="attendace_sheets_data" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Név</th>
                            <th scope="col">Dátum</th>
                            <th scope="col">Kezdés</th>
                            <th scope="col">Befejezés</th>
                            <th scope="col">Ledolgozott órák</th>
                            <th scope="col">Szünet</th>
                            <th scope="col">Állapot</th>
                            <th scope="col">Szerkesztés</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>

<div id="attendace_sheetsModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="attendace_sheets_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Szerkesztés</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name" class="labelUp">Név</label>
                            <input type="text" name="attendance_sheets[name]" id="name" class="form-control" disabled>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date" class="labelUp">Dátum</label>
                            <input type="date" name="attendance_sheets[date]" id="date" class="form-control" disabled>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="start_time" class="labelUp">Név</label>
                            <input type="time" name="attendance_sheets[start_time]" id="start_time" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="end_time" class="labelUp">Dátum</label>
                            <input type="time" name="attendance_sheets[end_time]" id="end_time" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="working_hours" class="labelUp">Ledolgozott órák</label>
                            <input type="number" step="0.5" name="attendance_sheets[working_hours]" id="working_hours" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="break" class="labelUp">Szünet</label>
                            <input type="number" name="attendance_sheets[break]" id="break" class="form-control">
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status" class="labelUp">Állapot</label>
                            <select name="attendance_sheets[status]" id="status" class="form-control status">
                                <option></option>
                                <option value="aláírt">aláírt</option>
                                <option value="szabadságon">szabadságon</option>
                            </select>
                            <div class="invalid-feedback">Nincsen kitöltve!</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="attendance_sheets[employee_id]" id="employee_id">
                    <input type="hidden" name="attendance_sheets[date]" id="date">
                    <input type="hidden" name="operation" id="operation">
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Változtat">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kilépés</button>
                </div>
            </div>
        </form>
    </div>
</div>