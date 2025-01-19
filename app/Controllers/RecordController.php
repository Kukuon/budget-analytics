<?php


namespace app\Controllers;

use app\Models\FinancialRecord;
use PDO;

class RecordController {
    private $financialRecordModel;
    private $db;

    public function __construct(PDO $pdo) {
        $this->financialRecordModel = new FinancialRecord($pdo);
        $this->db = $pdo;
    }

    public function getAllRecords() {
        return $this->financialRecordModel->getAllFinancialRecords();
    }

    public function addRecord($userId, $month, $year, $categoryId, $description, $attachment, $currency, $amount, $type) {
        $this->financialRecordModel->addFinancialRecord($userId, $month, $year, $categoryId, $description, $attachment, $currency, $amount, $type);
    }

    public function deleteRecord($id) {
        $this->financialRecordModel->deleteFinancialRecord($id);
    }
}
