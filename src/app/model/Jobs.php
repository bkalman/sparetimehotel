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
        if($user->getJob($_SESSION['employee_id'])[0] == 'igazgató' && in_array($x,['function.reservation','function.guests','function.report','function.employee','function.restaurant'])){
            return true;
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'igazgatóhelyettes' && in_array($x,['function.reservation','function.guests','function.report','function.employee','function.restaurant'])) {
            return true;
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'takarító' && in_array($x,[''])){
            return true;
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'porta' && in_array($x,['function.reservation','function.guests'])){
            return true;
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'karbantartó' && in_array($x,['function.report'])){
            return true;
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'hr' && in_array($x,['function.employee'])){
            return true;
        } else if($user->getJob($_SESSION['employee_id'])[0] == 'séf' && in_array($x,['function.restaurant'])){
            return true;
        }
    }

    public static function getCurrentUserAccessRight(){
        $user = Employees::getCurrentUser();
        if(is_null($user)) return false;
        if($user->getJob($_SESSION['employee_id'])[0] == 'igazgató'){
            return 'function.all';

        } else if($user->getJob($_SESSION['employee_id'])[0] == 'igazgatóhelyettes'){
            return 'function.all';

        } else if($user->getJob($_SESSION['employee_id'])[0] == 'porta') {
            return 'function.reservation';

        } else if($user->getJob($_SESSION['employee_id'])[0] == 'karbantartó') {
            return 'function.report';

        } else if($user->getJob($_SESSION['employee_id'])[0] == 'hr') {
            return 'function.employee';

        } else if($user->getJob($_SESSION['employee_id'])[0] == 'séf') {
            return 'function.restaurant';
        }
    }
}