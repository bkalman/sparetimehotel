<?php


namespace app\model;

use db\Database;

class Spending
{
    private $report_id;
    private $bill;
    private $price;

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
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    public static function findAll() {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM spending');
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function findById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('SELECT * FROM spending WHERE report_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function insert($reportId,$bill,$price) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('INSERT INTO spending(report_id,bill,price) VALUES (:report_id,:bill,:price)');
        $stmt->execute([
            ':report_id' => $reportId,
            ':bill' => $bill,
            ':price' => $price,
        ]);
    }

    public static function update($bill,$price) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('UPDATE spending SET price = :price WHERE bill = :bill');
        $stmt->execute([
            ':bill' => $bill,
            ':price' => $price,
        ]);
        return $bill.'-'.$price;
    }

    public static function delete($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare('DELETE FROM spending WHERE bill = ?');
        $stmt->execute([$id]);
        return $stmt;
    }
}