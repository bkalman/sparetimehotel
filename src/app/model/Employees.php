<?php


namespace app\model;
use db\Database;

class Employees
{
    private $employee_id;
    private $first_name;
    private $last_name;
    private $email;
    private $phone_number;
    private $job_id;
    private $address;
    private $password;

    private static $currentuser = null;

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employee_id;
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

    /**
     * @return mixed
     */
    public function getJobId()
    {
        return $this->job_id;
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



    public static function getJob($id){
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT jobs.title FROM jobs INNER JOIN employees ON jobs.job_id = employees.job_id WHERE employees.employee_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public static function findAll()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM employees");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS,self::class);
    }

    /**
     * @param $id
     * @return Employees
     */
    public static function findOneById($id)
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    public static function getCurrentUser()
    {
        if(is_null(self::$currentuser) && !empty($_SESSION['user_id'])) {
            self::$currentuser = self::findOneById($_SESSION['user_id']);
        }
        return self::$currentuser;
    }

    /**
     * @param $email
     * @return Employees
     */
    public static function findOneByEmail($email) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM employees WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchObject(self::class);
    }

    /**
     * @param $password
     * @return bool
     */
    public function doLogin($password) {
        if(password_verify($password, $this->password)){
            $_SESSION['user_id'] = $this->employee_id;
            return true;
        }
        return false;
    }
}