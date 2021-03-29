<?php


namespace app\model;
use db\Database;

class MenuRecommendation
{
    private $menu_id;
    private $recommendation_id;

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
    public function getRecommendationId()
    {
        return $this->recommendation_id;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM menu_recommendation');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
    }

    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM menu_recommendation WHERE menu_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public static function insert($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO menu_recommendation(menu_id,recommendation_id) VALUES (:menu_id,:recommendation_id)");
        $stmt->execute([
            ':menu_id' => $data[0],
            ':recommendation_id' => $data[1],
        ]);
    }

    public static function update($id,$data) {
        if (isset($data) || $data != '') {
            if (!empty(self::findOneById($id))) {
                if (self::findOneById($id)->getRecommendationId() != $data) {
                    self::delete($id);
                    self::insert([$id, $data]);
                }
            } else self::insert([$id, $data]);
        } else self::delete($id);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM menu_recommendation WHERE menu_id = ?");
        $stmt->execute([$id]);
    }
}