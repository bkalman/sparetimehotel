<?php


namespace app\model;
use db\Database;

class Jobs
{
    private $job_id;
    private $title;
    private $salary;

    private static $currentuser = null;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @return array
     */
    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM jobs');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return Jobs
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM jobs WHERE job_id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    public static function currentUserCan($x){
        $user = Employees::getCurrentUser();
        if(is_null($user)) return false;

        $functions = [
            'function.reservation' => ['igazgató','igazgatóhelyettes','porta',],
            'function.report' => ['igazgató','igazgatóhelyettes','karbantartó',],
            'function.employee' => ['igazgató','igazgatóhelyettes','hr',],
            'function.orders' => ['igazgató','igazgatóhelyettes','séf','pincér'],
            'function.menu' => ['igazgató','igazgatóhelyettes','séf'],
        ];

        if (in_array($user->getJob($_SESSION['employee_id'])[0], $functions[$x]))
            return true;
    }

    public static function getCurrentUserAccessRight(){
        $user = Employees::getCurrentUser();
        if(is_null($user)) return false;
        if($user->getJob($_SESSION['employee_id'])[0] == 'igazgató'){
            return 'function.all';
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'igazgatóhelyettes') {
            return 'function.all';
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'porta') {
            return 'function.reservation';
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'karbantartó') {
            return 'function.report';
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'hr') {
            return 'function.employee';
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'séf') {
            return 'function.restaurant';
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'pincér') {
            return 'function.restaurant';
        }
    }
}