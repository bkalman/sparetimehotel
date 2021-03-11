<?php
use app\model\Jobs;
use app\model\AttendanceSheets;
use app\model\Employees;

$sort = !empty($_GET['sort'])? $_GET['sort'] : 'asc';
$id = !empty($_GET['id'])? $_GET['id'] : $_SESSION['user_id'];
$date = !empty($_GET['date'])? $_GET['date'] : date('Ym');
$year = substr($date,0,4);
$month = substr($date,4);
/** @var AttendanceSheets[] $attendanceSheets */
/** @var Employees[] $employees */
$attendanceSheets = Jobs::currentUserCan('function.employee')? AttendanceSheets::findOneById($id,$sort,$year,$month) : AttendanceSheets::findOneById($_SESSION['user_id'],$sort,$year,$month);
$employees = Employees::findAll();

?>
<section id="container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Jelenléti ív</h1>
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
                        <?php if($sort == 'asc'): ?>
                            <a href="index.php?controller=function&action=attendanceSheet&sort=desc">Dátum
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-sort-alpha-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.082 5.629L9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371h-1.781zm1.57-.785L11 2.687h-.047l-.652 2.157h1.351z"/>
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645V14zM4.5 2.5a.5.5 0 0 0-1 0v9.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L4.5 12.293V2.5z"/>
                                </svg>
                            </a>
                        <?php elseif ($sort == 'desc'): ?>
                            <a href="index.php?controller=function&action=attendanceSheet">Dátum
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-sort-alpha-up" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.082 5.629L9.664 7H8.598l1.789-5.332h1.234L13.402 7h-1.12l-.419-1.371h-1.781zm1.57-.785L11 2.687h-.047l-.652 2.157h1.351z"/>
                                    <path d="M12.96 14H9.028v-.691l2.579-3.72v-.054H9.098v-.867h3.785v.691l-2.567 3.72v.054h2.645V14zm-8.46-.5a.5.5 0 0 1-1 0V3.707L2.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L4.5 3.707V13.5z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        </th>
                        <th scope="col"">
                            Kezdés
                        </th>
                        <th scope="col">
                            Befejezés
                        </th>
                        <th scope="col">
                            Ledolgozott órák
                        </th>
                        <th scope="col">
                            Szünet
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(Jobs::currentUserCan('function.employee')): ?>
                        <tr>
                            <td>
                                <select name="guestName" id="guestName" class="form-control">
                                    <?php foreach($employees as $k => $v): ?>
                                        <option value="<?=$v->getEmployeeId()?>" <?= $id == $v->getEmployeeId() ? 'selected' : '' ?> ><?=$v->getLastName()?> <?=$v->getFirstName()?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="year" id="year" class="form-control-plaintext" style="width:auto;display:inherit"></select>
                                <select name="month" id="month" class="form-control-plaintext" style="width:auto;display:inherit"></select>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach($attendanceSheets as $a => $s): ?>
                        <tr>
                            <td scope="row"><?=Employees::findOneById($s->getEmployeeId())->getLastName()?> <?=Employees::findOneById($s->getEmployeeId())->getFirstName()?></td>
                            <td><?=$s->getYear()?>.<?=$n = strlen($s->getMonth()) == 1 ? '0'.$s->getMonth() : $s->getMonth()?>.<?=$n = strlen($s->getDay()) == 1 ? '0'.$s->getDay() : $s->getDay()?></td>
                            <td><?=$s->getStartTime()?></td>
                            <td><?=$s->getEndTime()?></td>
                            <td><?=$s->getWorkingHours()?></td>
                            <td><?=$s->getBreak()?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>