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

$controllerName = !empty($_GET['controller'])? ucfirst($_GET['controller']).'Controller' : 'ProductController';
$actionName = !empty($_GET['action'])? 'action'.ucfirst($_GET['action']) : 'actionIndex';

$content = '404';

if($controllerName == 'HyperlinkController') {
    if($actionName == 'actionHome') {
        $content = 'src/app/view/home/index.php';
    }
}
include('src/app/view/template/mainTemplate.php');