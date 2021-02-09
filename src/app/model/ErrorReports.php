<?php


namespace app\model;
use db\Database;
use PDO;

class ErrorReports
{
    private $id;
    private $roomId;
    private $storey;
    private $status;
    private $report;

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
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * @return mixed
     */
    public function getStorey()
    {
        return $this->storey;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @return array
     */
    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM error_reports');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return ErrorReports
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM error_reports WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetchObject(self::class);
    }
}