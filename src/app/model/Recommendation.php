<?php


namespace app\model;
use db\Database;

class Recommendation
{
    private $recommendation_id;
    private $title;

    /**
     * @return mixed
     */
    public function getRecommendationId()
    {
        return $this->recommendation_id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM recommendation');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
    }

    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM recommendation WHERE recommendation_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }
    public function load($data) {
        foreach ($this->loadable as $item) {
            if(array_key_exists($item,$data) && !empty($data[$item])) {
                $this->$item = $data[$item];
            }
        }
    }

    public function insert() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO recommendation(title) VALUES (?)");
        $stmt->execute([$this->title]);
        if($stmt) {
            $this->recommendation_id = $conn->lastInsertId();
        }
        return self::findOneById($conn->lastInsertId());
    }

    public static function update($id,$data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE recommendation SET title WHERE recommendation_id =?");
        $stmt->execute([$id]);
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM recommendation WHERE recommendation_id = ?");
        $stmt->execute([$id]);
    }
}