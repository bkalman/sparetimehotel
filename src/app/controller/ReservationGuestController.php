<?php


namespace app\controller;

use app\model\Guests;
use app\model\Orders;
use app\model\RoomBooking;
use db\Database;
use app\model\Menu;
use app\model\Allergens;
use app\model\Recommendation;
use app\model\MenuRecommendation;

class ReservationGuestController extends MainController
{
    protected $controllerName = 'reservationGuest';

    public function actionDate() {
        if(empty($_SESSION['reservation'])) {
            $_SESSION['reservation']['start_date'] = $_POST['reservation']['start_date'];
            $_SESSION['reservation']['end_date'] = $_POST['reservation']['end_date'];
            $_SESSION['reservation']['adult'] = $_POST['reservation']['adult'];
            $_SESSION['reservation']['child'] = $_POST['reservation']['child'];
        }
        return $this->render('room',[]);
    }

    public function actionRoom() {
        if (!empty($_SESSION['reservation'])) {
            if(!empty($_POST['reservation']['room_id'])) {
                $_SESSION['reservation']['room_id'] = $_POST['reservation']['room_id'];
                fwrite(fopen('src/app/view/reservationGuest/orders.php','w'),'');
                return $this->render('menu',[]);
            } else header('location: index.php?controller=reservationGuest&action=date');
        } else header('location: index.php');
    }

    public function actionMenu() {
        if (!empty($_SESSION['reservation'])) {
            if(!empty($_SESSION['reservation']['room_id'])) {
                return $this->render('personalData', []);
            } else header('location: index.php?controller=reservationGuest&action=date');
        } else header('location: index.php');
    }

    public function actionPersonalData() {
        if (!empty($_SESSION['reservation'])) {
            $_SESSION['reservation']['personalData'] = $_POST['personalData'];
        } else header('location: index.php');
    }



    public function actionInsert() {
        if (isset($_POST["operation"])) {
            $price = Menu::findOneById(!empty($_POST['reservation']['menu']['breakfast']) ? $_POST['reservation']['menu']['breakfast'] : 0)->getPrice()+Menu::findOneById(!empty($_POST['reservation']['menu']['lunch']) ? $_POST['reservation']['menu']['lunch'] : 0)->getPrice()+Menu::findOneById(!empty($_POST['reservation']['menu']['dinner']) ? $_POST['reservation']['menu']['dinner'] : 0)->getPrice();
            $id = count(!empty($_SESSION['reservation']['menu']) ? $_SESSION['reservation']['menu'] : []);

            $_SESSION['reservation']['menu'][$id]['date'] = $_POST['reservation']['menu']['date'];
            $_SESSION['reservation']['menu'][$id]['breakfast'] = !empty($_POST['reservation']['menu']['breakfast']) ? Menu::findOneById($_POST['reservation']['menu']['breakfast'])->getName() : null;
            $_SESSION['reservation']['menu'][$id]['lunch'] = !empty($_POST['reservation']['menu']['lunch']) ? Menu::findOneById($_POST['reservation']['menu']['lunch'])->getName() : null;
            $_SESSION['reservation']['menu'][$id]['dinner'] = !empty($_POST['reservation']['menu']['dinner']) ? Menu::findOneById($_POST['reservation']['menu']['dinner'])->getName() : null;
            $_SESSION['reservation']['menu'][$id]['price'] = $price;
        }
        fwrite(fopen('src/app/view/reservationGuest/orders.php','w'),json_encode($_SESSION['reservation']['menu']));
    }

    public function actionFetch()
    {
        $conn = Database::getConnection();
        $query = '';
        $query .= 'SELECT * FROM menu ';
        $order = ['menu.name','','menu.price','menu.current'];

        if (!empty($_POST["search"]["value"])) {
            $query .= 'WHERE name LIKE "%' . $_POST["search"]["value"] . '%" ';
            $query .= 'OR price LIKE "%' . $_POST["search"]["value"] . '%" ';
        }

        if (!empty($_POST["order"])) {
            $query .= 'ORDER BY ' . $order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        } else { $query .= 'ORDER BY name DESC '; }
        if (!empty($_POST["length"]) && $_POST["length"] != -1) {
            $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $filtered_rows = $stmt->rowCount();

        $data = [];

        foreach ($result as $v) {
            if ($v['current'] == 1) {
                $allergens = [];
                $stmtAllergens = $conn->prepare('SELECT * FROM menu_allergens WHERE menu_id = ?');
                $stmtAllergens->execute([$v['menu_id']]);
                $resultAllergens = $stmtAllergens->fetchAll();
                foreach ($resultAllergens as $r)
                    $allergens[] = ' '.Allergens::findOneById($r['allergen_id'])->getName();

                $sub_array = [];
                $sub_array[count($sub_array)] = $v['name'];
                $sub_array[count($sub_array)] = $allergens;
                $sub_array[count($sub_array)] = $v["price"].' Ft';
                $sub_array[count($sub_array)] = !empty(MenuRecommendation::findOneById($v["menu_id"])) ? Recommendation::findOneById(MenuRecommendation::findOneById($v["menu_id"])->getRecommendationId())->getTitle() : '';

            }
            $data[count($data)] = $sub_array;
        }

        $output = [
            "draw" => intval(!empty($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => $filtered_rows,
            "recordsFiltered" => Menu::getRowCount(),
            "data" => $data
        ];
        fwrite(fopen('src/app/view/menu/fetch.php','w'),json_encode($output));
        header('location: src/app/view/menu/fetch.php');
    }

    public function actionFetchSingle() {
        $conn = Database::getConnection();
        if(!empty($_POST["room_id"]))
        {
            $output = [];
            $stmt = $conn->prepare('SELECT * FROM menu WHERE room_id = ?');
            $stmt->execute([$_POST['room_id']]);

            $result = $stmt->fetchAll();


            foreach($result as $row)
            {
                $stmtAllergens = $conn->prepare('SELECT * FROM menu_allergens WHERE room_id = ?');
                $stmtAllergens->execute([$row["room_id"]]);
                $resultAllergens = $stmtAllergens->fetchAll();
                $allergens = [];
                foreach ($resultAllergens as $r)
                    $allergens[] = $r['allergen_id'];

                $output["room_id"] = $row["room_id"];
                $output["name"] = $row["name"];
                $output['allergens'] = $allergens;
                $output["price"] = $row["price"];
                $output["current"] = $row["current"];
                $output["recommendation"] = !empty(MenuRecommendation::findOneById($row["room_id"])) ? MenuRecommendation::findOneById($row["room_id"])->getRecommendationId() : '';
            }
            fwrite(fopen('src/app/view/menu/fetchSingle.php','w'),json_encode($output));
            header('location: src/app/view/menu/fetchSingle.php');
        }
    }

    public function actionDelete() {
        print_r($_SESSION['reservation']['menu']);
        if(!empty($_SESSION['reservation'])) {

            $_SESSION['reservation']['menu'][$_POST["order_id"]] = '';
            unset($_SESSION['reservation']['menu'][$_POST["order_id"]]);
            fwrite(fopen('src/app/view/reservationGuest/orders.php','w'),json_encode($_SESSION['reservation']['menu']));
        }

    }

    public function actionDone() {
        if(!empty($_SESSION['reservation'])) {
            $conn = Database::getConnection();
            $conn->beginTransaction();
                $guests = new Guests();
                $guests->load([
                    'first_name' => $_POST['reservation']['first_name'],
                    'last_name' => $_POST['reservation']['last_name'],
                    'email' => $_POST['reservation']['email'],
                    'phone_number' => $_POST['reservation']['phone_number'][0].$_POST['reservation']['phone_number'][1] == '06' ? $_POST['reservation']['phone_number'] : '06'.$_POST['reservation']['phone_number'],
                ]);
                $guestId = $guests->insert();

                $roomBooking = new RoomBooking();
                $roomBooking->load([
                    'guest_id' => $guestId,
                    'adult' => $_SESSION['reservation']['adult'],
                    'child' => $_SESSION['reservation']['child'],
                    'room_id' => $_SESSION['reservation']['room_id'],
                    'start_date' => $_SESSION['reservation']['start_date'],
                    'end_date' => $_SESSION['reservation']['end_date'],
                ]);
                $roomBooking->insert();

                if (!empty($_SESSION['reservation']['menu'])) {
                    foreach ($_SESSION['reservation']['menu'] as $m) {
                        $orders = new Orders();
                        print_r($m);
                        $orders->load([
                            'guest_id' => $guestId,
                            'date' => $m['date'],
                            'breakfast' => !empty($m['breakfast']) ? Menu::findOneByName($m['breakfast']) : null,
                            'lunch' => !empty($m['lunch']) ? Menu::findOneByName($m['lunch']) : null,
                            'dinner' => !empty($m['dinner']) ? Menu::findOneByName($m['dinner']) : null,
                        ]);
                        $orders->insert();
                    }
                }
            $conn->commit();

            $_SESSION['reservation'] = '';
            unset($_SESSION['reservation']);
        }
    }
}