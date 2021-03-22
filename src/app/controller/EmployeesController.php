<?php


namespace app\controller;
use app\model\Employees;
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
                    header('Location: index.php');
                    exit;
                } else {
                    header('Location: index.php?controller=hyperlink&action=employees&login=false');
                    echo 'Sikertelen bejelentkezés';
                }
            } else {
                header('Location: index.php?controller=hyperlink&action=employees&login=false');
                echo 'Sikertelen bejelentkezés';
            }
        } else {
            header('Location: index.php?controller=hyperlink&action=employees&login=false');
        }
    }

    public function actionLogout() {
        $_SESSION['employee_id'] = '';
        unset($_SESSION['employee_id']);
        header('Location: index.php');
    }

    public function actionInsert() {
        //Nem működik
        //$passwordVerify = $_POST['employee']['password'] == $_POST['employee']['password_repeat'] ? '' : 'Nem egyező jelszavak';
        //$phoneVerify = preg_match("/^[06][0-9]\d{1,2}[0-9]\d{7}$/",$_POST['employee']['phone_number']) ? '' : 'Érvénytelen telefonszám';
        //$addressVerify = preg_match("/^[0-9]{4}[ ][a-zA-Z]+[, ][a-zA-Z0-9.]+[ ][0-9.]+$/",$_POST['employee']['address'])? '' : 'Érvénytelen lakcím';

        //if($passwordVerify == '' || $phoneVerify == '') {
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
                ];

                if ($_POST["operation"] == "Felvétel") {
                    $employees->load($employee);
                    $result = $employees->insert();
                    if(!empty($result))
                    {
                        fwrite(fopen('src/app/view/employees/msg.php','w'),'Sikeres felvétel!');
                    }
                } else if ($_POST["operation"] == "Változtat") {
                    $result = Employees::update($employee);
                    if(!empty($result))
                    {
                        fwrite(fopen('src/app/view/employees/msg.php','w'),'Sikeres adatszerkesztés!');
                    }
                }
                header('location: src/app/view/employees/msg.php');
            }
        //} else echo $passwordVerify.$phoneVerify;
    }

    public function actionFetch()
    {
        $conn = Database::getConnection();
        $query = '';
        $query .= 'SELECT * FROM jobs INNER JOIN employees ON jobs.job_id = employees.job_id ';
        $order = ['last_name','first_name','title'];
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
            $query .= 'ORDER BY ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else { $query .= 'ORDER BY last_name DESC '; }
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
            $sub_array[count($sub_array)] = '<button type="button" name="update" id="' . $v["employee_id"] . '" class="btn btn-warning update"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-repeat" viewBox="-4 -4 25 25"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg></button>';
            $sub_array[count($sub_array)] = '<button type="button" name="delete" id="' . $v["employee_id"] . '" class="btn btn-danger btn-xs delete"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></button>';

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

    public function actionUpdate() {
        $conn = Database::getConnection();
        if(!empty($_POST["employee_id"]))
        {
            $output = array();
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
           }
            fwrite(fopen('src/app/view/employees/fetchSingle.php','w'),json_encode($output));
            header('location: src/app/view/employees/fetchSingle.php');
        }
    }

    public function actionDelete() {
        if(!empty($_POST["employee_id"]))
        {
            $result = Employees::delete($_POST['employee_id']);
            if ($result != '') fwrite(fopen('src/app/view/employees/msg.php','w'),'Sikeres törlés!');
            header('location: src/app/view/employees/msg.php');
        }
    }
}