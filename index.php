<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);

error_reporting(E_ALL);

require('vendor/autoload.php');

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

session_start();

if(empty($_GET['controller']) && empty($_GET['action'])) {
    $_SESSION['reservation'] = '';
    unset($_SESSION['reservation']);
}

$controllerName = !empty($_GET['controller'])? ucfirst($_GET['controller']).'Controller' : 'ViewController';
$actionName = !empty($_GET['action'])? 'action'.ucfirst($_GET['action']) : 'actionHome';

$content = '404';
$style = '';
if($actionName != 'actionHome') $style = '<link rel="stylesheet" href="css/nav.css">';

$else = false;

if($controllerName == 'ViewController') {
    $controller = new \app\controller\ViewController();
    $content = !empty($_GET['action'])? $controller->actionIndex($_GET['action']) : $controller->actionIndex('home');
} else if($controllerName == 'EmployeesController') {
    $controller = new \app\controller\EmployeesController();
    if($actionName == 'actionLoginIndex') {
        $content = $controller->actionLoginIndex();
    } else if($actionName == 'actionLogin') {
        $content = $controller->actionLogin();
    } else if($actionName == 'actionLogout') {
        $content = $controller->actionLogout();
    } else if($actionName == 'actionInsert') {
        $content = $controller->actionInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionDelete') {
        $content = $controller->actionDelete();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else if($actionName == 'actionActive') {
        $content = $controller->actionActive();
    } else $else = true;
} else if($controllerName == 'ErrorReportsController') {
    $controller = new \app\controller\ErrorReportsController();
    if($actionName == "actionInsert") {
        $content = $controller->actionInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionInsertDiary') {
        $content = $controller->actionInsertDiary();
    } else if($actionName == 'actionCheckIn') {
        $content = $controller->actionCheckIn();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else if($actionName == 'actionFetchSingleDiary') {
        $content = $controller->actionFetchSingleDiary();
    } else if($actionName == 'actionFetchSingleDate') {
        $content = $controller->actionFetchSingleDate();
    } else if($actionName == 'actionFindAll') {
        $content = $controller->actionFindAll();
    } else if($actionName == 'actionDeleteBill') {
        $content = $controller->actionDeleteBill();
    } else $else = true;
} else if($controllerName == 'RoomBookingController') {
    $controller = new \app\controller\RoomBookingController();
    if($actionName == "actionInsert") {
        $content = $controller->actionInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionCheck') {
        $content = $controller->actionCheck();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else $else = true;
} else if($controllerName == 'GuestsController') {
    $controller = new \app\controller\GuestsController();
    if($actionName == "actionInsert") {
        $content = $controller->actionInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionCheckIn') {
        $content = $controller->actionCheckIn();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else if($actionName == 'actionFindAll') {
        $content = $controller->actionFindAll();
    } else $else = true;
} else if($controllerName == 'OrdersController') {
    $controller = new \app\controller\OrdersController();
    if($actionName == "actionOrderInsert") {
        $content = $controller->actionInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionDelete') {
        $content = $controller->actionDelete();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else $else = true;
} else if($controllerName == 'MenuController') {
    $controller = new \app\controller\MenuController();
    if($actionName == "actionMenuInsert") {
        $content = $controller->actionMenuInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionDelete') {
        $content = $controller->actionDelete();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else $else = true;
} else if($controllerName == 'ReservationGuestController') {
    $controller = new \app\controller\ReservationGuestController();
    if($actionName == "actionDate") {
        $content = $controller->actionDate();
    } else if($actionName == "actionRoom") {
        $content = $controller->actionRoom();
    } else if($actionName == "actionMenu") {
        $content = $controller->actionMenu();
    } else if($actionName == "actionPersonalData") {
        $content = $controller->actionPersonalData();
    } else if($actionName == "actionFetch") {
        $content = $controller->actionFetch();
    } else if($actionName == "actionInsert") {
        $content = $controller->actionInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionDelete') {
        $content = $controller->actionDelete();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    } else if($actionName == 'actionDone') {
        $content = $controller->actionDone();
    } else $else = true;
} else if($controllerName == 'AttendanceSheetsController') {
    $controller = new \app\controller\AttendanceSheetsController();
    if($actionName == "actionInsertMonth") {
        $content = $controller->actionInsertMonth();
    } else if($actionName == "actionUpdate") {
        $content = $controller->actionUpdate();
    } else if($actionName == "actionUpdateStatus") {
        $content = $controller->actionUpdateStatus();
    } else if($actionName == "actionFetch") {
        $content = $controller->actionFetch();
    } else if($actionName == "actionFetchSingle") {
        $content = $controller->actionFetchSingle();
    } else $else = true;
} else $else = true;

if ($else == true)
    header('location: index.php');

include('src/app/view/template/mainTemplate.php');