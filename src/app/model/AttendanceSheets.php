<?php


namespace app\model;
use db\Database;

class AttendanceSheets
{
    private $employee_id;
    private $date;
    private $start_time;
    private $end_time;
    private $working_hours;
    private $break;
    private $status;

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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * @return mixed
     */
    public function getWorkingHours()
    {
        return $this->working_hours;
    }


    /**
     * @return mixed
     */
    public function getBreak()
    {
        return $this->break;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    public static function findAll($sort) {
        if ($sort == 'nameAsc') {
            $sql = 'SELECT * FROM attendance_sheets';
        } else if ($sort == 'nameDesc') {
            $sql ='SELECT attendance_sheets.* FROM attendance_sheets INNER JOIN employees ON attendance_sheets.employee_id = employees.employee_id ORDER BY employees.last_name DESC, employees.first_name DESC';
        }
        $conn = Database::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findOneById($id,$sort,$year,$month) {
        if ($sort == 'asc') {
            $sql = 'SELECT * FROM attendance_sheets WHERE employee_id = :id AND attendance_sheets.year = :y AND attendance_sheets.month = :m';
        } else if ($sort == 'desc') {
            $sql ='SELECT attendance_sheets.* FROM attendance_sheets INNER JOIN employees ON attendance_sheets.employee_id = employees.employee_id WHERE attendance_sheets.employee_id = :id  AND attendance_sheets.year = :y AND attendance_sheets.month = :m ORDER BY attendance_sheets.year DESC, attendance_sheets.month DESC, attendance_sheets.day DESC';
        }
        $conn = Database::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id,':y' => $year,':m' => $month]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function update($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE attendance_sheets SET start_time = :start_time,end_time = :end_time,working_hours = :working_hours,break = :break,status = :status WHERE employee_id = :employee_id AND date = :date');
        $stmt->execute([
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':working_hours' => $data['working_hours'],
            ':break' => $data['break'],
            ':status' => $data['status'],
            ':employee_id' => $data['employee_id'],
            ':date' => $data['date'],
        ]);
    }

    public static function insert($data) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('INSERT INTO attendance_sheets(employee_id, date, start_time, end_time, working_hours, break, status) VALUES (:employee_id, :date, :start_time, :end_time, :working_hours, :break, :status)');
        $stmt->execute([
            ':employee_id' => $data['employee_id'],
            ':date' => $data['date'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':working_hours' => $data['working_hours'],
            ':break' => $data['break'],
            ':status' => $data['status'],
        ]);
    }

    public static function getRowCount()
    {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM room_booking");
        $stmt->execute();
        return $stmt->rowCount();
    }
}