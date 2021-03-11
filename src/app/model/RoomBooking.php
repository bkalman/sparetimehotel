<?php


namespace app\model;
use db\Database;

class RoomBooking
{
    private $room_booking_id;
    private $guest_id;
    private $adult;
    private $child;
    private $room_id;
    private $start_date;
    private $end_date;
    private $breakfast;
    private $lunch;
    private $dinner;
    private $check_in;

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
    public function getGuestId()
    {
        return $this->guest_id;
    }

    /**
     * @return mixed
     */
    public function getAdult()
    {
        return $this->adult;
    }

    /**
     * @return mixed
     */
    public function getChild()
    {
        return $this->child;
    }

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
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->end_date;
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

    /**
     * @return mixed
     */
    public function getCheckIn()
    {
        return $this->check_in;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM room_booking ORDER BY start_date DESC');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return RoomBooking
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM room_booking WHERE room_booking_id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    public static function updateCheck($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE room_booking SET check_in = :ch WHERE room_booking_id = :id');
        $stmt->execute([
            ':ch' => self::findOneById($id)->getCheckIn() == 1 ? 0 : 1,
            ':id' => $id
        ]);
    }
}