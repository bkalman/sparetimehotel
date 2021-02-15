<?php


namespace app\model;
use db\Database;
use PDO;

class Jobs
{
    private $id;
    private $title;
    private $salery;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getSalery()
    {
        return $this->salery;
    }

    /**
     * @return array
     */
    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM jobs');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return Jobs
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM jobs WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    public static function currentUserCan($x){
        $user = Employees::getCurrentUser();
        if(is_null($user)) return false;
        if($user->getJob($_SESSION['user_id']) == 'igazgató'){
            return true;
        } else if($user->getJob($_SESSION['user_id']) == 'takarító' && in_array($x,[''])){
            return true;
        } else if($user->getJob($_SESSION['user_id']) == 'porta' && in_array($x,['function.guests'])){
            return true;
        } else if($user->getJob($_SESSION['user_id']) == 'karbantartó' && in_array($x,['function.reports'])){
            return true;
        } else if($user->getJob($_SESSION['user_id']) == 'hr' && in_array($x,['function.employees'])){
            return true;
        } else if($user->getJob($_SESSION['user_id']) == 'séf' && in_array($x,['function.restaurant'])){
            return true;
        }
    }

    public static function getCurrentUserAccessRight(){
        $user = Employees::getCurrentUser();
        if(is_null($user)) return false;
        if($user->getJob($_SESSION['user_id']) == 'igazgató'){
            return 'function.all';

        } else if($user->getJob($_SESSION['user_id']) == 'porta') {
            return 'function.guests';

        } else if($user->getJob($_SESSION['user_id']) == 'karbantartó') {
            return 'function.reports';

        } else if($user->getJob($_SESSION['user_id']) == 'hr') {
            return 'function.employees';

        } else if($user->getJob($_SESSION['user_id']) == 'séf') {
            return 'function.restaurant';

        }
    }
}