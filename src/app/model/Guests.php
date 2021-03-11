<?php


namespace app\model;
use db\Database;

class Guests
{
    private $guest_id;
    private $first_name;
    private $last_name;
    private $email;
    private $phone_number;

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
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }


    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM guests");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM guests WHERE guest_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }
}