<?php


namespace app\controller;


class MainController
{
    protected $controllerName = '';
    protected $title = '';
    protected function render($view,$data = []) {
        extract($data);
        ob_start();
        include("src/app/view/{$this->controllerName}/{$view}.php");

        echo '<footer>';
        echo '<p>A honlapot Kálmán Bence készítette</p>';
        echo '</footer>';
        echo '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>';
        echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>';
        echo '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>';
        echo '<script src="js/jquery.datatables.min.js"></script>';

        $files = scandir("src/app/view/{$this->controllerName}/js");
        foreach ($files as $file) {
            if($file != '.' && $file != '..')
            echo "<script src='src/app/view/{$this->controllerName}/js/{$file}'></script>";
        }
        return ob_get_clean();
    }
}