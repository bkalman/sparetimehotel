<?php
use app\model\Employees;
$sort = $_POST["sort"];
if ($sort == 'desc') {
    $sort = 'asc';
} else {
    $sort = 'desc';
}
$query = "SELECT * FROM tbl_employee ORDER BY " . $_POST["columnName"] . " " . $_POST["sort"];

$sql ='SELECT attendance_sheets.* FROM attendance_sheets INNER JOIN employees ON attendance_sheets.employee_id = employees.employee_id WHERE attendance_sheets.employee_id = 1 ORDER BY employees.'.$_POST["columnName"].', employees.'.$_POST["sort"];
$attendanceSheet = \app\model\AttendanceSheets::findOneById($sql);

$output = '  
      <tr>  
           <th><a class="column_sort" id="name" data-order="' . $sort . '" href="#">Név</a></th>  
           <th><a class="column_sort" id="date" data-order="' . $sort . '" href="#">Dátum</a></th>  
           <th><a class="column_sort" id="start" data-order="' . $sort . '" href="#">Kezdés</a></th>  
           <th><a class="column_sort" id="end" data-order="' . $sort . '" href="#">Befejezés</a></th>  
           <th><a class="column_sort" id="workingHours" data-order="' . $sort . '" href="#">Ledolgozott órák</a></th>  
           <th><a class="column_sort" id="break" data-order="' . $sort . '" href="#">Szünet</a></th>  
      </tr>  
 ';
?>
<?php foreach($attendanceSheet as $a => $s): ?>
    <tr>
        <td scope="row"><?=Employees::findOneById($s->getEmployeeId())->getLastName()?> <?=Employees::findOneById($s->getEmployeeId())->getFirstName()?></td>
        <td><?=$s->getYear()?>.<?=$n = strlen($s->getMonth()) == 1 ? '0'.$s->getMonth() : $s->getMonth()?>.<?=$n = strlen($s->getDay()) == 1 ? '0'.$s->getDay() : $s->getDay()?></td>
        <td><?=$s->getStartTime()?></td>
        <td><?=$s->getEndTime()?></td>
        <td><?=$s->getWorkingHours()?></td>
        <td><?=$s->getBreak()?></td>
    </tr>
<?php endforeach; ?>