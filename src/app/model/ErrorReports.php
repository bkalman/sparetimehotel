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
    private $started;

    private $loadable = ['room_id','place','storey','status','report','started'];

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
     * @return mixed
     */
    public function getStarted()
    {
        return $this->started;
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
        $stmt = $conn->prepare("SELECT * FROM error_reports WHERE report_id = ?");
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
        $stmt = $conn->prepare("INSERT INTO error_reports(room_id,place,storey,status,report,started) VALUES (:room,:place,:storey,:status,:report,:started)");
        $stmt->execute([
            ':room' => $this->room_id,
            ':place' => $this->place,
            ':storey' => $this->storey,
            ':status' => $this->status,
            ':report' => $this->report,
            ':started' => $this->started,
        ]);
        if($stmt) {
          $this->report_id = $conn->lastInsertId();
        }
    }

    public static function update($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE error_reports SET room_id = :room_id,place = :place,storey = :storey,status = :status,report = :report,started = :started');
        $stmt->execute([
            ':room' => $data['room_id'],
            ':place' => $data['place'],
            ':storey' => $data['storey'],
            ':status' => $data['status'],
            ':report' => $data['report'],
            ':started' => $data['started'],
        ]);
    }

    public static function getRowCount()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM error_reports");
        $stmt->execute();
        return $stmt->rowCount();
    }
}