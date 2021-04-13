<?php


namespace app\model;
use db\Database;

class Menu
{
    private $menu_id;
    private $name;
    private $price;
    private $current;

    private $loadable = ['name','price','current'];

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
        $stmt = $conn->prepare("SELECT * FROM menu WHERE menu_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public static function findOneByName($name) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM menu WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetchObject(self::class)->getMenuId();
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
        $stmt = $conn->prepare("INSERT INTO menu(name,price,current) VALUES (:name,:price,:current)");
        $stmt->execute([
            ':name' => $this->name,
            ':price' => $this->price,
            ':current' => $this->current,
        ]);
        if($stmt) {
            $this->menu_id = $conn->lastInsertId();
        }
        return self::findOneById($conn->lastInsertId());
    }

    public static function update($data) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("UPDATE menu SET name = :name,price = :price,current = :current WHERE menu_id = :menu_id");
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':current' => $data['current'],
            ':menu_id' => $data['menu_id'],
        ]);

        return $stmt;
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmtMenu = $conn->prepare('SELECT * FROM orders WHERE breakfast = ? OR lunch = ? OR dinner = ?');
        $stmtMenu->execute([$id,$id,$id,]);
        $result = $stmtMenu->fetchAll(\PDO::FETCH_CLASS,self::class);
        if (empty($result) && $id != 0) {
            MenuAllergens::deleteAll($id);
            MenuRecommendation::delete($id);
            $stmt = $conn->prepare("DELETE FROM menu WHERE menu_id = ?");
            $stmt->execute([$id]);
        } else return 'false-in-menu';
    }

    public static function getRowCount()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM menu");
        $stmt->execute();
        return $stmt->rowCount();
    }
}