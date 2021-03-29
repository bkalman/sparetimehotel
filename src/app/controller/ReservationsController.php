<?php


namespace app\controller;

use app\model\Guests;
use app\model\RoomBooking;
use db\Database;

class ReservationsController
{
    public function actionInsert() {
        if (isset($_POST["operation"])) {
            $food = [
                'room_booking_id' => $_POST['reservations']['room_booking_id'],
                'last_name' => $_POST['reservations']['last_name'],
                'first_name' => $_POST['reservations']['first_name'],
                'email' => $_POST['reservations']['email'],
                'phone_number' => $_POST['reservations']['phone_number'],
                'adult' => $_POST['reservations']['adult'],
                'child' => $_POST['reservations']['child'],
                'room_id' => $_POST['reservations']['room_id'],
                'start_date' => $_POST['reservations']['start_date'],
                'end_date' => $_POST['reservations']['end_date'],
            ];

            if ($_POST["operation"] == "Hozzáad") {
                $roomBooking = new RoomBooking();
                $roomBooking->load($food);
                $result = $roomBooking->insert();

                if(!empty($result))
                {
                    fwrite(fopen('src/app/view/reservations/msg.php','w'),'Sikeres felvétel!');
                }
            } else if ($_POST["operation"] == "Változtat") {
                $result = RoomBooking::update($food);
                if(!empty($result))
                {
                    fwrite(fopen('src/app/view/reservations/msg.php','w'),'Sikeres adatszerkesztés!');
                }
            }
            header('location: src/app/view/reservations/msg.php');
        }
    }

    public function actionFetch()
    {
        $conn = Database::getConnection();
        $query = '';
        $query .= 'SELECT * FROM room_booking INNER JOIN guests ON room_booking.guest_id = guests.guest_id ';
        $order = ['room_booking.room_booking_id','guests.lastname','','','room_booking.room_id','room_booking.start_date','room_booking.end_date'];

        if (!empty($_POST["search"]["value"])) {
            $query .= 'WHERE guests.last_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR guests.first_name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR room_booking.room_booking_id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR room_booking.room_id LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR room_booking.start_date LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR room_booking.end_date LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (!empty($_POST["order"])) {
            $query .= 'ORDER BY ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else { $query .= 'ORDER BY room_booking_id DESC '; }
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $filtered_rows = $stmt->rowCount();

        $data = [];

        foreach ($result as $v) {
            $sub_array = [];
            $sub_array[count($sub_array)] = $v['room_booking_id'];
            $sub_array[count($sub_array)] = Guests::findOneById($v['guest_id'])->getLastName().' '.Guests::findOneById($v['guest_id'])->getFirstName();
            $sub_array[count($sub_array)] = $v['email'];
            $sub_array[count($sub_array)] = $v['phone_number'];
            $sub_array[count($sub_array)] = $v['adult'];
            $sub_array[count($sub_array)] = $v['child'];
            $sub_array[count($sub_array)] = $v['room_id'];
            $sub_array[count($sub_array)] = $v['start_date'];
            $sub_array[count($sub_array)] = $v['end_date'];
            $sub_array[count($sub_array)] = '<button type="button" name="update" id="' . $v["room_booking_id"] . '" class="btn btn-warning update"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-repeat" viewBox="-4 -4 25 25"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg></button>';
            $sub_array[count($sub_array)] = '<button type="button" name="check" id="' . $v["room_booking_id"] . '" class="btn btn-danger btn-xs check"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-bookmark-check" viewBox="-4 -4 25 25"><path fill-rule="evenodd" d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z"/><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z"/></svg></button>';

            $data[count($data)] = $sub_array;
        }

        $output = [
            "draw" => intval(!empty($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => $filtered_rows,
            "recordsFiltered" => RoomBooking::getRowCount(),
            "data" => $data
        ];
        fwrite(fopen('src/app/view/reservations/fetch.php','w'),json_encode($output));
        header('location: src/app/view/reservations/fetch.php');
    }

    public function actionFetchSingle() {
        $conn = Database::getConnection();
        if(!empty($_POST["room_booking_id"]))
        {
            $output = [];
            $stmt = $conn->prepare('SELECT * FROM room_booking WHERE room_booking_id = ?');
            $stmt->execute([$_POST['room_booking_id']]);

            $result = $stmt->fetchAll();

            foreach($result as $row)
            {
                $output["room_booking_id"] = $row["room_booking_id"];
            }
            fwrite(fopen('src/app/view/reservations/fetchSingle.php','w'),json_encode($output));
            header('location: src/app/view/reservations/fetchSingle.php');
        }
    }

    public function actionDelete() {
        if(!empty($_POST["room_booking_id"]))
        {
            $result = RoomBooking::delete($_POST['room_booking_id']);
            if ($result != '')
                fwrite(fopen('src/app/view/reservations/msg.php','w'),'Sikeres törlés!');
            header('location: src/app/view/reservations/msg.php');
        }
    }
}