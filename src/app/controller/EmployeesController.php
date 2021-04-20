<?php


namespace app\controller;
use app\model\Employees;
use app\model\JobHistory;
use app\model\Jobs;
use db\Database;

class EmployeesController extends MainController
{
    protected $controllerName = 'employees';

    public function actionLoginIndex() {
        $this->title = 'employees';
        return $this->render('login',[]);
    }

    public function actionLogin() {
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $user = Employees::findOneByEmail($_POST['email']);
            if(!empty($user)) {
                if ($user->doLogin($_POST['password'])) {
                    return 'login-true';
                }
            }
        }
        return 'login-false';
    }

    public function actionLogout() {
        $_SESSION['employee_id'] = '';
        unset($_SESSION['employee_id']);
        header('Location: index.php');
    }

    public function actionInsert() {
        $employees = new Employees();
        if (isset($_POST["operation"])) {
            $employee = [
                'first_name' => $_POST['employee']['first_name'],
                'last_name' => $_POST['employee']['last_name'],
                'email' => $_POST['employee']['email'],
                'phone_number' => $_POST['employee']['phone_number'][0].$_POST['employee']['phone_number'][1] != '06' ? '06'.$_POST['employee']['phone_number'] : $_POST['employee']['phone_number'],
                'job_id' => $_POST['employee']['job_id'],
                'zip' => $_POST['employee']['zip'],
                'city' => $_POST['employee']['city'],
                'street_address' => $_POST['employee']['street_address'],
                'house_number' => $_POST['employee']['house_number'],
                'floor_door' => $_POST['employee']['floor_door'],
                'password' => $_POST['employee']['password'],
                'employee_id' => $_POST['employee']['employee_id'],
                'active' => $_POST['employee']['active'],
            ];

            if ($_POST["operation"] == "Felvétel") {
                $employees->load($employee);
                $employees->insert();
                JobHistory::insert($employee['employee_id'],date('Y-m-d'));
            } else if ($_POST["operation"] == "Változtat") {
                Employees::update($employee);
                JobHistory::update($employee['employee_id'],$_POST['employee']['started_date'],$_POST['employee']['end_date']);
            }
        }
    }

    public function actionFetch()
    {
        $conn = Database::getConnection();
        $query = '';
        $query .= 'SELECT * FROM jobs INNER JOIN employees ON jobs.job_id = employees.job_id ';
        $order = ['last_name','title','','','','','active'];
        if (isset($_POST["search"]["value"])) {
            $query .= 'WHERE first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR title LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR phone_number LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR email LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR zip LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR city LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR street_address LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR house_number LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR floor_door LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (isset($_POST["order"])) {
            if($order[$_POST['order']['0']['column']] == 'active') $query .= 'ORDER BY ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
            if($order[$_POST['order']['0']['column']] != 'active') $query .= 'ORDER BY active DESC, ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else { $query .= 'ORDER BY active DESC, last_name DESC '; }
        if (isset($_POST["length"]) && $_POST["length"] != -1) {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $filtered_rows = $stmt->rowCount();

        $data = [];
        foreach ($result as $v) {
            $sub_array = [];
            $sub_array[count($sub_array)] = $v["last_name"].' '.$v["first_name"];
            $sub_array[count($sub_array)] = $v["title"];
            $sub_array[count($sub_array)] = $v["email"];
            $sub_array[count($sub_array)] = $v["phone_number"];
            $sub_array[count($sub_array)] = $v["zip"].' '.$v["city"].' '.$v["street_address"].' '.$v["house_number"].' '.$v["floor_door"];
            $sub_array[count($sub_array)] = '<button type="button" name="update" id="' . $v["employee_id"] . '" class="btn btn-warning update"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pencil" viewBox="-4 -4 25 25"><path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/></svg></button>';
            $sub_array[count($sub_array)] = $v["active"] == 1 ? '<button type="button" name="active" id="' . $v["employee_id"] . '" class="btn btn-success active"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-check-fill" viewBox="-4 -4 25 25"><path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></svg></button>' : '<button type="button" name="active" id="' . $v["employee_id"] . '" class="btn btn-danger active"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-dash" viewBox="-4 -4 25 25"><path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/><path fill-rule="evenodd" d="M11 7.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/></svg>/button>';
            $data[count($data)] = $sub_array;
        }

        $output = [
            "draw" => intval(!empty($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => $filtered_rows,
            "recordsFiltered" => Employees::getRowCount(),
            "data" => $data
        ];
        fwrite(fopen('src/app/view/employees/fetch.php','w'),json_encode($output));
        header('location: src/app/view/employees/fetch.php');
    }

    public function actionFetchSingle() {
        $conn = Database::getConnection();
        if(!empty($_POST["employee_id"]))
        {
            $output = [];
            $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ? LIMIT 1");
            $stmt->execute([$_POST["employee_id"]]);
            $result = $stmt->fetchAll();
            foreach($result as $row)
            {
                $output["first_name"] = $row["first_name"];
                $output["last_name"] = $row["last_name"];
                $output["email"] = $row["email"];
                $output["phone_number"] = $row["phone_number"];
                $output["job_id"] = $row["job_id"];
                $output['zip'] = $row['zip'];
                $output['city'] = $row['city'];
                $output['street_address'] = $row['street_address'];
                $output['house_number'] = $row['house_number'];
                $output['floor_door'] = $row['floor_door'];
                $output['active'] = $row['active'];
                $output['started_date'] = JobHistory::findOneById($_POST["employee_id"])->getStartDate();
                $output['end_date'] = JobHistory::findOneById($_POST["employee_id"])->getEndDate();
           }
            fwrite(fopen('src/app/view/employees/fetchSingle.php','w'),json_encode($output));
            header('location: src/app/view/employees/fetchSingle.php');
        }
    }

    public function actionActive() {
        if(!empty($_POST["employee_id"]))
        {
            Employees::updateActive($_POST['employee_id'],$_POST['active']);
            JobHistory::update($_POST['employee_id'],$_POST['active'] == 0 ? date('Y-m-d') : null);
        }
    }
}