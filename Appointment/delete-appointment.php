<?php
require_once '../db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: appointment.php?error=" . urlencode("ไม่พบ ID การนัดหมาย"));
    exit();
}

$appointment_id = $_GET['id'];

try {
    // ลบการนัดหมาย
    $delete_stmt = $pdo->prepare("DELETE FROM appointments WHERE appointment_id = :id");
    $delete_stmt->execute([':id' => $appointment_id]);

    // ตรวจสอบว่ามีการลบข้อมูลจริงหรือไม่
    if ($delete_stmt->rowCount() > 0) {
        $success_message = "ลบการนัดหมายเรียบร้อยแล้ว";
    } else {
        $error_message = "ไม่พบข้อมูลการนัดหมายที่ต้องการลบ";
    }
} catch (PDOException $e) {
    $error_message = "เกิดข้อผิดพลาดในการลบข้อมูล: " . $e->getMessage();
}

// ส่งกลับไปยังหน้า appointment.php พร้อมข้อความแจ้งเตือน
if (isset($success_message)) {
    header("Location: appointment.php?success=" . urlencode($success_message));
} else {
    header("Location: appointment.php?error=" . urlencode($error_message));
}
exit();