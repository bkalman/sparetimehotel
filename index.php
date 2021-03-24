<?php

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
if($actionName != 'actionHome') $style = '<link rel="stylesheet" href="css/nav.css">';

if($controllerName == 'HyperlinkController') {
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
    }
} else if($controllerName == 'FunctionController') {
    $controller = new \app\controller\FunctionsController();
    if($actionName == 'actionReservation') {
        $content = $controller->actionReservation();
    } else if($actionName == 'actionReports') {
        $content = $controller->actionReports();
    } else if($actionName == 'actionEmployee') {
        $content = $controller->actionEmployee();
    } else if($actionName == 'actionRestaurant') {
        $content = $controller->actionRestaurant();
    } else if($actionName == 'actionAttendanceSheet') {
        $content = $controller->actionAttendanceSheet();
    }

} else if($controllerName == 'ErrorReportController') {
    $controller = new \app\controller\ErrorReportsController();
    if($actionName == 'actionInsertIndex') {
        $content = $controller->actionInsertIndex();
    } else if($actionName == 'actionDelete') {
        $content = $controller->actionDelete();
    } else if($actionName == 'actionUpload') {
        $content = $controller->actionUpload();
    }
} else if($controllerName == 'RoomBookingController') {
    $controller = new \app\controller\RoomBookingController();
    if($actionName == 'actionCheckIn') {
        $content = $controller->actionCheckIn();
    }
} else if($controllerName == 'RestaurantController') {
    $controller = new \app\controller\RestaurantController();
    if($actionName == "actionOrderInsert") {
        $content = $controller->actionOrderInsert();
    } else if($actionName == 'actionFetch') {
        $content = $controller->actionFetch();
    } else if($actionName == 'actionDelete') {
        $content = $controller->actionDelete();
    } else if($actionName == 'actionFetchSingle') {
        $content = $controller->actionFetchSingle();
    }
}

include('src/app/view/template/mainTemplate.php');


