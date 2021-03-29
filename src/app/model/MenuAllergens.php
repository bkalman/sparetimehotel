<?php


namespace app\model;
use db\Database;

class MenuAllergens
{
    private $menu_id;
    private $allergen_id;

        /**
     * @return mixed
     */
    public function getMenuId()
    {
        return $this->menu_id;
    }

    /**
     * @return mixed
     */
    public function getAllergenId()
    {
        return $this->allergen_id;
    }

    public static function findById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM menu_allergens WHERE menu_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
    }

    public static function insert($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('INSERT INTO menu_allergens(menu_id, allergen_id) VALUES (:menu_id, :allergen_id)');
        $stmt->execute([
            ':menu_id' => $data[0],
            ':allergen_id' => $data[1],
        ]);
    }

    public static function update($id,$data) {
        $allergens = [];
        foreach (self::findById($id) as $a)
            $allergens[] = $a->getAllergenId();

        $result1 = array_diff($data,$allergens);
        $result2 = array_diff($allergens,$data);
        $ids[] = $result1;
        $ids[] = $result2;
        $ids = array_unique($ids[0]+$ids[1]);
        foreach ($ids as $i) {
            echo $i;
            if (in_array($i,$allergens)) {
                self::delete($id, $i);
            } else self::insert([$id,$i]);
        }

        //Ha törlés és írás is van, csak írja
    }

    public static function delete($id,$data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('DELETE FROM menu_allergens WHERE menu_id = :menu_id AND allergen_id = :allergen_id');
        $stmt->execute([
            ':menu_id' => $id,
            ':allergen_id' => $data,
        ]);
    }
    public static function deleteAll($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('DELETE FROM menu_allergens WHERE menu_id = :menu_id');
        $stmt->execute([
            ':menu_id' => $id,
        ]);
    }
}