<?php


namespace app\controller;


use app\model\Menu;
use db\Database;
use app\model\Allergens;
use app\model\MenuAllergens;
use app\model\Recommendation;
use app\model\MenuRecommendation;

class MenuController
{
    public function actionMenuInsert() {
        if (isset($_POST["operation"])) {
            $food = [
                'menu_id' => $_POST['menu']['menu_id'],
                'name' => $_POST['menu']['name'],
                'price' => $_POST['menu']['price'],
                'current' => !empty($_POST['menu']['current']) ? $_POST['menu']['current'] : 0,
            ];

            $allergens = [];
            for ($i = 1; $i <= count(Allergens::findAll()); $i++) {
                if(!empty($_POST['allergens'][$i]) && $_POST['allergens'][$i] == $i)
                    $allergens[] = $_POST['allergens'][$i];
            }

            if ($_POST["operation"] == "Hozzáad") {
                $menu = new Menu();
                $menu->load($food);
                $result = $menu->insert();
                if (!empty($_POST['menu']['recommendation'])) MenuRecommendation::insert([$result->getMenuId(),$_POST['menu']['recommendation']]);
                foreach ($allergens as $allergen)
                    MenuAllergens::insert([$result->getMenuId(),$allergen]);

                if(!empty($result))
                {
                    fwrite(fopen('src/app/view/menu/msg.php','w'),'Sikeres felvétel!');
                }
            } else if ($_POST["operation"] == "Változtat") {
                $result = Menu::update($food);
                MenuAllergens::update($food['menu_id'],$allergens);
                MenuRecommendation::update($food['menu_id'],$_POST['menu']['recommendation']);
                if(!empty($result))
                {
                    fwrite(fopen('src/app/view/menu/msg.php','w'),'Sikeres adatszerkesztés!');
                }
            }
            header('location: src/app/view/menu/msg.php');
        }
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
            $allergens = [];
            $stmtAllergens = $conn->prepare('SELECT * FROM menu_allergens WHERE menu_id = ?');
            $stmtAllergens->execute([$v['menu_id']]);
            $resultAllergens = $stmtAllergens->fetchAll();
            foreach ($resultAllergens as $r)
                $allergens[] = ' '.Allergens::findOneById($r['allergen_id'])->getName();

            $sub_array = [];
            $sub_array[count($sub_array)] = $v['name'];
            $sub_array[count($sub_array)] = $allergens;
            $sub_array[count($sub_array)] = $v["price"];
            $sub_array[count($sub_array)] = $v["current"] == 1 ? 'igen' : 'nem';
            $sub_array[count($sub_array)] = !empty(MenuRecommendation::findOneById($v["menu_id"])) ? Recommendation::findOneById(MenuRecommendation::findOneById($v["menu_id"])->getRecommendationId())->getTitle() : '';
            $sub_array[count($sub_array)] = '<button type="button" name="update" id="' . $v["menu_id"] . '" class="btn btn-warning update"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-repeat" viewBox="-4 -4 25 25"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg></button>';
            $sub_array[count($sub_array)] = '<button type="button" name="delete" id="' . $v["menu_id"] . '" class="btn btn-danger btn-xs delete"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg></button>';

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
        if(!empty($_POST["menu_id"]))
        {
            $output = [];
            $stmt = $conn->prepare('SELECT * FROM menu WHERE menu_id = ?');
            $stmt->execute([$_POST['menu_id']]);

            $result = $stmt->fetchAll();


            foreach($result as $row)
            {
                $stmtAllergens = $conn->prepare('SELECT * FROM menu_allergens WHERE menu_id = ?');
                $stmtAllergens->execute([$row["menu_id"]]);
                $resultAllergens = $stmtAllergens->fetchAll();
                $allergens = [];
                foreach ($resultAllergens as $r)
                    $allergens[] = $r['allergen_id'];

                $output["menu_id"] = $row["menu_id"];
                $output["name"] = $row["name"];
                $output['allergens'] = $allergens;
                $output["price"] = $row["price"];
                $output["current"] = $row["current"];
                $output["recommendation"] = !empty(MenuRecommendation::findOneById($row["menu_id"])) ? MenuRecommendation::findOneById($row["menu_id"])->getRecommendationId() : '';
            }
            fwrite(fopen('src/app/view/menu/fetchSingle.php','w'),json_encode($output));
            header('location: src/app/view/menu/fetchSingle.php');
        }
    }

    public function actionDelete() {
        if(!empty($_POST["menu_id"]))
        {
            $result = Menu::delete($_POST['menu_id']);
            if ($result != '')
                fwrite(fopen('src/app/view/menu/msg.php','w'),'Sikeres törlés!');
            header('location: src/app/view/menu/msg.php');
        }
    }
}