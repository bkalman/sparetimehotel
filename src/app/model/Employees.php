<?php


namespace app\model;
use db\Database;
use PDO;

class Employees
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $phoneNumber;
    private $jobId;
    private $departmentId;
    private $address;
    private $password;
    private static $currentuser = null;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
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
        return $this->phoneNumber;
    }

    /**
     * @return mixed
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @return mixed
     */
    public function getDepartmentId()
    {
        return $this->departmentId;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    public static function findOneById($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM employees WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * @param $email
     * @return Employees
     */
    public static function findOneByEmail($email) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM employees WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * @return Employees|null
     */
    public static function getCurrentUser()
    {
        if(is_null(self::$currentuser) && !empty($_SESSION['user_id'])) {
            self::$currentuser = self::findOneById($_SESSION['user_id']);
        }
        return self::$currentuser;
    }

    /**
     * @param $password
     * @return bool
     */
    public function doLogin($password) {
        if(password_verify($password, $this->password)){
            $_SESSION['user_id'] = $this->id;
            return true;
        }
        return false;
    }
}