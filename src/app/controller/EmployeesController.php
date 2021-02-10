<?php


namespace app\controller;
use app\model\Employees;

class EmployeesController extends MainController
{
    public function actionLogin() {
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $user = Employees::findOneByEmail($_POST['email']);
            if($user->doLogin($_POST['password'])) {
                header('Location: index.php');
                exit;
            } else {
                echo 'Sikertelen bejelentkezés';
            }
        }
    }

    public function actionLogout() {
        $_SESSION['user_id'] = '';
        unset($_SESSION['user_id']);
        header('Location: index.php');
    }
}