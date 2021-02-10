<?php


namespace app\controller;


class ViewController extends MainController
{
    protected $controllerName = '';

    public function actionIndex($page) {
        $this->controllerName = $page;
        $this->title = $page;
        return $this->render('index',[]);
    }
}