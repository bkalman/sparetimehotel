<?php


namespace app\model;
use db\Database;

class Allergens
{
    private $allergen_id;
    private $name;

    /**
     * @return mixed
     */
    public function getAllergenId()
    {
        return $this->allergen_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM allergens');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
    }

    public static function findOneById($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM allergens WHERE allergen_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }
}