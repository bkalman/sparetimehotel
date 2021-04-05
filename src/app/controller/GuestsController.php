<?php


namespace app\controller;

use app\model\Guests;
use db\Database;

class GuestsController extends MainController
{
    public function actionInsert() {
        if (isset($_POST["operationUser"])) {
            $reservation = [
                'guest_id' => !empty($_POST['reservations']['guest_id']) ? $_POST['reservations']['guest_id'] : null,
                'last_name' => $_POST['guest']['last_name'],
                'first_name' => $_POST['guest']['first_name'],
                'email' => $_POST['guest']['email'],
                'phone_number' => $_POST['guest']['phone_number'][0].$_POST['guest']['phone_number'][1] != '06' ? '06'.$_POST['guest']['phone_number'] : $_POST['guest']['phone_number'],
            ];
            if ($_POST["operationUser"] == "Regisztrál") {
                $guests = new Guests();
                $guests->load($reservation);
                $guests->insert();
            } else if ($_POST["operationUser"] == "Változtat")
                Guests::update($reservation);
        }
    }

    public static function actionFindAll() {
        $result = Guests::findAllFetch();

        $data = [];
        foreach ($result as $v) {
            $sub_array = [];
            $sub_array['guest_id'] = $v['guest_id'];
            $sub_array['first_name'] = $v['first_name'];
            $sub_array['last_name'] = $v['last_name'];
            $sub_array['email'] = $v['email'];
            $sub_array['phone_number'] = $v['phone_number'];
            $data[count($data)] = $sub_array;
        }
        fwrite(fopen('src/app/view/reservations/guests.php', 'w'), json_encode($data));
        header('location: src/app/view/reservations/guests.php');
    }
}