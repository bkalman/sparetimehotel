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

    public function insert() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO room_booking(room_booking_id,guest_id,email,phone_number,adult,child,room_id,start_date,end_date) VALUES (:room_booking_id,:guest_id,:email,:phone_number,:adult,:child,:room_id,:start_date,:end_date)");
        $stmt->execute([
            ':room_booking_id' => $this->room_booking_id,
            ':guest_id' => $this->guest_id,
            ':email' => $this->email,
            ':phone_number' => $this->phone_number,
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

        $stmt = $conn->prepare("UPDATE room_booking SET name = :name,price = :price,current = :current WHERE room_booking_id = :room_booking_id");
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':current' => $data['current'],
            ':room_booking_id' => $data['room_booking_id'],
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