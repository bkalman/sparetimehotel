<?php


namespace app\model;
use db\Database;

class AttendanceSheets
{
    private $employee_id;
    private $year;
    private $month;
    private $day;
    private $start_time;
    private $end_time;
    private $working_hours;
    private $break;

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
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
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
}