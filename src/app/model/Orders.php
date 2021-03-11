<?php


namespace app\model;
use db\Database;

class Orders
{
    private $room_booking_id;
    private $date;
    private $breakfast;
    private $lunch;
    private $dinner;

    /**
     * @return mixed
     */
    public function getRoomBookingId()
    {
        return $this->room_booking_id;
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
}