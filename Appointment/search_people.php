<?php
require_once '../db.php';

$search = $_POST['search'];
$type = $_POST['type'];

try {
    if ($type === 'patient') {
        $sql = "SELECT patient_id AS id, CONCAT(first_name, ' ', last_name) AS name 
                FROM patients 
                WHERE first_name LIKE :search OR last_name LIKE :search 
                ORDER BY first_name 
                LIMIT 10";
    } else {
        $sql = "SELECT dentist_id AS id, CONCAT(first_name, ' ', last_name) AS name 
                FROM dentists 
                WHERE first_name LIKE :search OR last_name LIKE :search 
                ORDER BY first_name 
                LIMIT 10";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}