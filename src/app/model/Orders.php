<?php


namespace app\model;
use db\Database;

class Orders
{
    private $order_id;
    private $guest_id;
    private $date;
    private $breakfast;
    private $lunch;
    private $dinner;

    private  $loadable = ['guest_id','date','breakfast','lunch','dinner'];

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @return mixed
     */
    public function getGuestId()
    {
        return $this->guest_id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getBreakfast()
    {
        return $this->breakfast;
    }

    /**
     * @return mixed
     */
    public function getLunch()
    {
        return $this->lunch;
    }

    /**
     * @return mixed
     */
    public function getDinner()
    {
        return $this->dinner;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM orders");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
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
        $stmt = $conn->prepare("INSERT INTO orders(guest_id,date,breakfast,lunch,dinner) VALUES (:guest_id,:date,:breakfast,:lunch,:dinner)");
        $stmt->execute([
            ':guest_id' => $this->guest_id,
            ':date' => $this->date,
            ':breakfast' => $this->breakfast,
            ':lunch' => $this->lunch,
            ':dinner' => $this->dinner,
        ]);
        if($stmt) {
            $this->order_id = $conn->lastInsertId();
        }
        return $stmt;
    }

    public static function update($data) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("UPDATE orders SET guest_id = :guest_id,date = :date,breakfast = :breakfast,lunch = :lunch,dinner = :dinner WHERE order_id = :order_id");
        $stmt->execute([
            ':guest_id' => $data['guest_id'],
            ':date' => $data['date'],
            ':breakfast' => $data['breakfast'],
            ':lunch' => $data['lunch'],
            ':dinner' => $data['dinner'],
            ':order_id' => $data['order_id'],
        ]);

        return $stmt;
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
        $stmt->execute([$id]);
        return $stmt;
    }

    public static function getRowCount()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM orders");
        $stmt->execute();
        return $stmt->rowCount();
    }
}