<?php


namespace app\model;
use db\Database;

class Menu
{
    private $menu_id;
    private $name;
    private $price;
    private $current;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->current;
    }


    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM menu");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
    }

    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM menu WHERE menu_id LIKE ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public static function findOneByName($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT menu_id FROM menu WHERE name LIKE ?");
        $stmt->execute(['"%'.$name.'%"']);
        return $stmt->fetch();
    }
}