<?php


namespace app\controller;


use app\model\AttendanceSheets;
use app\model\Employees;

class AttendanceSheetsController
{

    public function actionInsertMonth() {
        $conn = Database::getConnection();
        $query = '';
        $query .= 'SELECT * FROM attendance_sheets INNER JOIN employees ON attendance_sheets.employee_id = employees.employee_id ';
        $order = ['employees.last_name','attendance_sheets.year','','','','','status'];

        if (!empty($_POST["search"]["value"])) {
            $query .= 'WHERE employees.last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR employees.first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR attendance_sheets.year LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR attendance_sheets.month LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (!empty($_POST["order"])) {
            $query .= 'ORDER BY ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else { $query .= 'ORDER BY employees.last_name DESC '; }
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $filtered_rows = $stmt->rowCount();


        $data = [];
        foreach ($result as $v) {
            $sub_array = [];
            $sub_array[count($sub_array)] = $v['employee_id'];
            $sub_array[count($sub_array)] = Employees::findOneById($v['employee_id'])->getLastName().' '.Employees::findOneById($v['employee_id'])->getFirstName();
            $sub_array[count($sub_array)] = $v['year'].'-'.$v['month'].'-'.$v['day'];
            $sub_array[count($sub_array)] = $v['start_time'];
            $sub_array[count($sub_array)] = $v['end_time'];
            $sub_array[count($sub_array)] = $v['working_hours'];
            $sub_array[count($sub_array)] = $v['break'];
            $sub_array[count($sub_array)] = '<button type="button" name="check" id="' . $v["employee_id"] . '" class="btn btn-success btn-xs check"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg></button>';

            $data[count($data)] = $sub_array;
        }

        $output = [
            "draw" => intval(!empty($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => $filtered_rows,
            "recordsFiltered" => AttendanceSheets::getRowCount(),
            "data" => $data
        ];
        fwrite(fopen('src/app/view/reservations/fetch.php','w'),json_encode($output));
        header('location: src/app/view/reservations/fetch.php');
    }

    public function actionFetch() {

    }
}