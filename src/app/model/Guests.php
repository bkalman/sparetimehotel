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

    private $loadable = ['first_name','last_name','email','phone_number'];

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
        $stmt = $conn->prepare("SELECT * FROM guests ORDER BY last_name, first_name");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findAllFetch() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM guests ORDER BY last_name, first_name");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM guests WHERE guest_id = :id");
        $stmt->execute([':id' => $id]);
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
        $stmt = $conn->prepare("INSERT INTO guests (first_name,last_name,email,phone_number) VALUES (:first_name,:last_name,:email,:phone_number)");
        $stmt->execute([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
        ]);
        if($stmt) {
            $this->guest_id = $conn->lastInsertId();
        }
        return $this->guest_id;
    }

    public static function update($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("UPDATE guests SET first_name = :first_name,last_name = :last_name,email = :email,phone_number = :phone_number");
        $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
        ]);
        return $stmt;
    }
}