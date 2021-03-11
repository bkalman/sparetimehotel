<?php


namespace app\model;
use db\Database;

class Rooms
{
    private $room_id;
    private $storey;
    private $bed;
    private $extras;
    private $price;

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->room_id;
    }

    /**
     * @return mixed
     */
    public function getStorey()
    {
        return $this->storey;
    }

    /**
     * @return mixed
     */
    public function getBed()
    {
        return $this->bed;
    }

    /**
     * @return mixed
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return array
     */
    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM rooms');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findAllStorey() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT storey FROM rooms GROUP BY storey');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return Rooms
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM rooms WHERE room_id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }
}