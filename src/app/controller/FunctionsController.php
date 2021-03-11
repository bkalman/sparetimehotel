<?php


namespace app\controller;

class FunctionsController extends MainController
{
    protected $controllerName = 'functions';

    public function actionReservation(){
        return $this->render('reservation',[]);
    }

    public function actionReports(){
        return $this->render('reports',[]);
    }

    public function actionEmployee(){
        return $this->render('employee',[]);
    }

    public function actionRestaurant(){
        return $this->render('restaurant',[]);
    }

    public function actionAttendanceSheet(){
        return $this->render('attendanceSheet',[]);
    }
}