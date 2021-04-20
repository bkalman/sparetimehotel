<?php


namespace app\controller;


use app\model\AttendanceSheets;
use app\model\Employees;
use app\model\Jobs;
use db\Database;

class AttendanceSheetsController
{

    public function actionInsertMonth() {

        $employees = Employees::findAll();

        foreach ($employees as $e) {
            for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                AttendanceSheets::insert([
                    'employee_id' => $e->getEmployeeId(),
                    'date' => date('Y').'-'.date('m').'-'.$i,
                    'start_time' => '08:00:00',
                    'end_time' => '16:00:00',
                    'working_hours' => 7.5,
                    'break' => 30,
                    'status' => null,
                ]);
            }
        }

    }

    public function actionFetch() {
        $conn = Database::getConnection();
        $query = '';
        $query .= 'SELECT * FROM attendance_sheets INNER JOIN employees ON attendance_sheets.employee_id = employees.employee_id '.(Jobs::currentUserCan('function.employee') ? '' : "WHERE attendance_sheets.employee_id = {$_SESSION['employee_id']} ");

        $order = ['employees.last_name','date','','','','','status'];

        if (!empty($_POST["search"]["value"])) {
            $query .= (Jobs::currentUserCan('function.employee') ? 'WHERE ' : 'AND ').'employees.last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR attendance_sheets.date LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (!empty($_POST["order"])) {
            $query .= 'ORDER BY ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else { $query .= 'ORDER BY attendance_sheets.date DESC '; }
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
            $sub_array[count($sub_array)] = Employees::findOneById($v['employee_id'])->getLastName().' '.Employees::findOneById($v['employee_id'])->getFirstName();
            $sub_array[count($sub_array)] = $v['date'];
            $sub_array[count($sub_array)] = $v['start_time'];
            $sub_array[count($sub_array)] = $v['end_time'];
            $sub_array[count($sub_array)] = $v['working_hours'].' óra';
            $sub_array[count($sub_array)] = $v['break'].' perc';
            $sub_array[count($sub_array)] = '<select name="attendance_sheets[status]" id="status" class="form-control status" data-employee_id="'.$v['employee_id'].'" data-date="'.$v['date'].'"><option></option><option value="aláírt" '.($v['status'] == 'aláírt' ? 'selected' : '').'>aláírt</option><option value="szabadságon" '.($v['status'] == 'szabadságon' ? 'selected' : '').'>szabadságon</option></select>';
            $sub_array[count($sub_array)] = '<button type="button" name="update" id="update" data-employee_id="'. $v["employee_id"] .'" data-date="'.$v['date'].'" class="btn btn-warning update"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pencil" viewBox="-4 -4 25 25"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></button>';

            $data[count($data)] = $sub_array;
        }

        $output = [
            "draw" => intval(!empty($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => $filtered_rows,
            "recordsFiltered" => AttendanceSheets::getRowCount(),
            "data" => $data
        ];
        fwrite(fopen('src/app/view/attendanceSheets/fetch.php','w'),json_encode($output));
        header('location: src/app/view/attendanceSheets/fetch.php');
    }

    public function actionFetchSingle() {
        $conn = Database::getConnection();
        if(!empty($_POST["employee_id"]) && !empty($_POST["date"]))
        {
            $output = [];
            $stmt = $conn->prepare('SELECT * FROM attendance_sheets WHERE employee_id = :employee_id AND date = :date');
            $stmt->execute([
                ':employee_id' => $_POST['employee_id'],
                ':date' => $_POST['date'],
            ]);
            $result = $stmt->fetchAll();
            foreach($result as $row)
            {
                $output["name"] = Employees::findOneById($row["employee_id"])->getLastName().' '.Employees::findOneById($row["employee_id"])->getFirstName();
                $output["date"] = $row["date"];
                $output["start_time"] = $row['start_time'];
                $output["end_time"] = $row['end_time'];
                $output["working_hours"] = $row["working_hours"];
                $output["break"] = $row["break"];
                $output["status"] = $row["status"];
                $output["employee_id"] = $row["employee_id"];
            }
            fwrite(fopen('src/app/view/attendanceSheets/fetchSingle.php','w'),json_encode($output));
            header('location: src/app/view/attendanceSheets/fetchSingle.php');
        }
    }

    public function actionUpdateStatus() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE attendance_sheets SET status = :status WHERE employee_id = :employee_id AND date = :date');
        $stmt->execute([
            ':status' => $_POST['status'],
            ':employee_id' => $_POST['employee_id'],
            ':date' => $_POST['date'],
        ]);
    }

    public static function actionUpdate() {
            AttendanceSheets::update([
                'start_time' => $_POST['attendance_sheets']['start_time'],
                'end_time' => $_POST['attendance_sheets']['end_time'],
                'working_hours' => $_POST['attendance_sheets']['working_hours'],
                'break' => $_POST['attendance_sheets']['break'],
                'status' => $_POST['attendance_sheets']['status'],
                'employee_id' => $_POST['attendance_sheets']['employee_id'],
                'date' => $_POST['attendance_sheets']['date'],
            ]);
//        }
    }
}