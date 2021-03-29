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

    private $loadable = ['guest_id','email','phone_number','adult','child','room_id','start_date','end_date'];

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
        $stmt = $conn->prepare('SELECT * FROM room_booking');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return RoomBooking
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM room_booking WHERE room_booking_id = ?');
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
        $stmt = $conn->prepare("INSERT INTO room_booking(guest_id,adult,child,room_id,start_date,end_date,check_in) VALUES (:guest_id,:adult,:child,:room_id,:start_date,:end_date,0)");
        $stmt->execute([
            ':guest_id' => $this->guest_id,
            ':adult' => $this->adult,
            ':child' => $this->child,
            ':room_id' => $this->room_id,
            ':start_date' => $this->start_date,
            ':end_date' => $this->end_date,
        ]);
        if($stmt) {
            $this->room_booking_id = $conn->lastInsertId();
        }
        return self::findOneById($conn->lastInsertId());
    }

    public static function update($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE room_booking SET guest_id = :guest_id,adult = :adult,child = :child,room_id = :room_id,start_date = :start_date,end_date = :end_date WHERE room_booking_id = :room_booking_id");
        $stmt->execute([
            ':guest_id' => $data['guest_id'],
            ':adult' => $data['adult'],
            ':child' => $data['child'],
            ':room_id' => $data['room_id'],
            ':start_date' => $data['start_date'],
            ':end_date' => $data['end_date'],
            ':room_booking_id' => $data['room_booking_id'],
        ]);
        return $stmt;
    }

    public static function check($id) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("UPDATE room_booking SET check_in = :check_in WHERE room_booking_id = :room_booking_id");
        $stmt->execute([
            ':check_in' => self::findOneById($id)->getCheckIn() == 0 ? 1 : 0,
            ':room_booking_id' => $id,
        ]);

        return $stmt;
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM room_booking WHERE room_booking_id = ?");
        $stmt->execute([$id]);
    }

    public static function getRowCount()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM room_booking");
        $stmt->execute();
        return $stmt->rowCount();
    }
}