<?php


namespace app\model;
use db\Database;

class ErrorReports
{
    private $report_id;
    private $room_id;
    private $place;
    private $storey;
    private $status;
    private $report;

    private $loadable = ['room_id','place','storey','status','report'];

    /**
     * @return mixed
     */
    public function getReportId()
    {
        return $this->report_id;
    }

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->room_id;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
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
        $stmt = $conn->prepare("SELECT * FROM error_reports");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * @param $id
     * @return ErrorReports
     */
    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM error_reports WHERE room_id = ?");
        $stmt->execute([$id]);
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
        $stmt = $conn->prepare("INSERT INTO `error_reports`(`room_id`,`place`,`storey`,`status`,`report`) VALUES (:room,:place,:storey,:status,:report)");
        $stmt->execute([
            ':room' => $this->room_id,
            ':place' => $this->place,
            ':storey' => $this->storey,
            ':status' => $this->status,
            ':report' => $this->report
        ]);

        if($stmt) {
          $this->report_id = $conn->lastInsertId();
        }
    }

    public static function delete($id) {
        if (!is_null($id)) {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("DELETE FROM error_reports WHERE report_id = ?");
            $stmt->execute([$id]);
            return true;
        } else {
            return false;
        }
    }
}