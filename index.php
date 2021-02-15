<?php

use app\model\AttendanceSheets;
use app\model\Departments;
use app\model\Employees;
use app\model\ErrorReports;
use app\model\Guest;
use app\model\JobHistorys;
use app\model\Jobs;
use app\model\Menu;
use app\model\RoomBooking;
use app\model\Room;

ini_set('display_errors',1);
ini_set('display_startup_errors',1);

error_reporting(E_ALL);

require('vendor/autoload.php');

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

session_start();

$controllerName = !empty($_GET['controller'])? ucfirst($_GET['controller']).'Controller' : 'HyperlinkController';
$actionName = !empty($_GET['action'])? 'action'.ucfirst($_GET['action']) : 'actionHome';

$content = '404';
$style = '';
if($actionName != 'actionHome') $style = 'css/nav.css';

if($controllerName == 'HyperlinkController') {
    $controller = new \app\controller\ViewController();
    $content = !empty($_GET['action'])? $controller->actionIndex($_GET['action']) : $controller->actionIndex('home');
} else if($controllerName == 'EmployeesController') {
    $controller = new \app\controller\EmployeesController();
    if($actionName == 'actionLogin') {
        $content = $controller->actionLogin();
    } else if($actionName == 'actionLogout') {
        $content = $controller->actionLogout();
    }
} else if($controllerName == 'FunctionController') {
    $controller = new \app\controller\FunctionsController();
    if($actionName == 'actionGuests') {
        $content = $controller->actionGuests();
    } else if($actionName == 'actionReports') {
        $content = $controller->actionReports();
    }
    else if($actionName == 'actionEmployees') {
        $content = $controller->actionEmployees();
    }
    else if($actionName == 'actionRestaurant') {
        $content = $controller->actionRestaurant();
    }
}
include('src/app/view/template/mainTemplate.php');