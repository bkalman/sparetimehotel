<?php


namespace app\model;

use db\Database;

class Diary
{
    private $report_id;
    private $employee_id;
    private $started;
    private $finished;
    private $comment;

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
    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    /**
     * @return mixed
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @return mixed
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM diary');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findOneById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM diary WHERE report_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchObject(self::class);
    }

    public static function insert($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('INSERT INTO diary(report_id, employee_id, started, finished, comment) VALUES (:report_id,:employee_id,:started,:finished,:comment)');
        $stmt->execute([
            ':report_id' => $data['report_id'],
            ':employee_id' => $data['employee_id'],
            ':started' => $data['started'],
            ':finished' => $data['finished'],
            ':comment' => $data['comment'],
        ]);
    }

    public static function update($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE diary SET employee_id = :employee_id,started = :started,finished = :finished,comment = :comment WHERE report_id = :report_id');
        $stmt->execute([
            ':report_id' => $data['report_id'],
            ':employee_id' => $data['employee_id'],
            ':started' => $data['started'],
            ':finished' => $data['finished'],
            ':comment' => $data['comment'],
        ]);
    }
}