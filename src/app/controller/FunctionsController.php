<?php


namespace app\controller;

class FunctionsController extends MainController
{
    protected $controllerName = 'functions';

    public function actionGuests(){
        return $this->render('guests',[]);
    }

    public function actionReports(){
        return $this->render('reports',[]);
    }

    public function actionEmployees(){
        return $this->render('employees',[]);
    }

    public function actionRestaurant(){
        return $this->render('restaurant',[]);
    }
}