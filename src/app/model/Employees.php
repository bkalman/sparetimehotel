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
    private $zip;
    private $city;
    private $street_address;
    private $house_number;
    private $floor_door;
    private $password;

    private static $currentuser = null;
    private $loadable = ['first_name','last_name','email','phone_number','job_id','zip', 'city', 'street_address', 'house_number', 'floor_door','password'];

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
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    /**
     * @return mixed
     */
    public function getHouseNumber()
    {
        return $this->house_number;
    }

    /**
     * @return mixed
     */
    public function getFloorDoor()
    {
        return $this->floor_door;
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
        if(is_null(self::$currentuser) && !empty($_SESSION['employee_id'])) {
            self::$currentuser = self::findOneById($_SESSION['employee_id']);
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
            $_SESSION['employee_id'] = $this->employee_id;
            return true;
        }
        return false;
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
        $stmt = $conn->prepare("INSERT INTO employees(first_name, last_name, email, phone_number, job_id, zip, city, street_address, house_number, floor_door, password) VALUES (:first_name, :last_name, :email, :phone_number, :job_id, :zip, :city, :street_address, :house_number, :floor_door, :password)");
        $stmt->execute([
            ':first_name' => $this->first_name,
            ':last_name' => $this->last_name,
            ':email' => $this->email,
            ':phone_number' => $this->phone_number,
            ':job_id' => $this->job_id,
            ':zip' => $this->zip,
            ':city' => $this->city,
            ':street_address' => $this->street_address,
            ':house_number' => $this->house_number,
            ':floor_door' => $this->floor_door,
            ':password' => password_hash($this->password,PASSWORD_BCRYPT),
        ]);
        if($stmt) {
            $this->employee_id = $conn->lastInsertId();
        }
        return $stmt;
    }

    public static function update($data) {
        $conn = Database::getConnection();

        $stmt = $conn->prepare("UPDATE employees SET first_name = :first_name, last_name = :last_name, email = :email, phone_number = :phone_number, job_id = :job_id, zip = :zip,city = :city,street_address = :street_address,house_number = :house_number,floor_door = :floor_door, password = :password WHERE employee_id = :employee_id");
        $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':job_id' => $data['job_id'],
            ':zip' => $data['zip'],
            ':city' => $data['city'],
            ':street_address' => $data['street_address'],
            ':house_number' => $data['house_number'],
            ':floor_door' => $data['floor_door'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':employee_id' => $data['employee_id'],
        ]);
        return $stmt;
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
        $stmt->execute([$id]);
        return $stmt;
    }

    public static function getRowCount()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM employees");
        $stmt->execute();
        return $stmt->rowCount();
    }

}