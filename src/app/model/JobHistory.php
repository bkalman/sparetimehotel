<?php


namespace app\model;
use db\Database;

class JobHistory
{
    private $id;
    private $start_date;
    private $end_date;

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
     * @return array
     */
    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM job_history');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return JobHistory
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM job_history WHERE employee_id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }

    public static function insert($id,$date) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('INSERT INTO job_history (employee_id,start_date,end_date) VALUES (:employee_id,:start_date,null)');
        $stmt->execute([
            ':employee_id' => $id,
            ':start_date' => $date,
        ]);
        return $stmt->fetchObject(self::class);
    }

    public static function update($id,$startDate,$endDate) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE job_history SET start_date = :start_date,end_date = :end_date WHERE employee_id = :employee_id');
        $stmt->execute([
            ':employee_id' => $id,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
        ]);
        return $stmt->fetchObject(self::class);
    }
}