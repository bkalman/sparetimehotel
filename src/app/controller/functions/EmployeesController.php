<?php


namespace app\controller\functions;
use app\controller\MainController;
use app\model\Employees;
use app\model\Jobs;

class EmployeesController extends MainController
{
    protected $controllerName = 'functions';

    public function actionIndex(){
        return $this->render('employees',[]);
    }
}