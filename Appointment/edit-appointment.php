<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขการนัดหมาย - คลินิกทันตกรรม</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-lg-2 d-lg-block bg-purple sidebar collapse">
                <div class="position-sticky">
                    <div class="clinic-logo text-center py-4">
                        <img src="../IMAGE/lOGO.jpg" alt="Dental Clinic Logo" class="img-fluid rounded-circle mb-3"
                            width="80">
                        <h5 class="text-white">คลินิกทันตกรรม</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php">
                                <i class="fas fa-home"></i> หน้าหลัก
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#patientSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="fas fa-user"></i> ผู้ป่วย
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                            <ul class="collapse list-unstyled" id="patientSubmenu">
                                <li>
                                    <a class="nav-link" href="../Patient/add-patient.php"><i class="fas fa-plus"></i>
                                        เพิ่มผู้ป่วยใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="../Patient/patient.php"><i class="fas fa-list"></i>
                                        รายชื่อผู้ป่วย</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="../Patient/history-patient.php"><i
                                            class="fas fa-history"></i> ประวัติการรักษา</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#appointmentSubmenu" data-bs-toggle="collapse"
                                aria-expanded="false">
                                <i class="fas fa-calendar-alt"></i> นัดหมาย
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                            <ul class="collapse list-unstyled" id="appointmentSubmenu">
                                <li>
                                    <a class="nav-link" href="add-appointment.php"><i class="fas fa-plus"></i>
                                        สร้างนัดหมายใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link active" href="appointment.php"><i class="fas fa-list"></i>
                                        รายการนัดหมาย</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="schedule.php"><i class="fas fa-clock"></i>
                                        ตารางนัดหมาย</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#treatmentSubmenu" data-bs-toggle="collapse"
                                aria-expanded="false">
                                <i class="fas fa-tooth"></i> การรักษา
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                            <ul class="collapse list-unstyled" id="treatmentSubmenu">
                                <li>
                                    <a class="nav-link" href="../Treatment/treatment.php"><i class="fas fa-list-ul"></i>
                                        รายการการรักษา</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#doctorSubmenu" data-bs-toggle="collapse" aria-expanded="false">
                                <i class="fas fa-user-md"></i> แพทย์
                                <i class="fas fa-chevron-down float-end"></i>
                            </a>
                            <ul class="collapse list-unstyled" id="doctorSubmenu">
                                <li>
                                    <a class="nav-link" href="../Doctor/doctor.php"><i class="fas fa-list"></i>
                                        รายชื่อแพทย์</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="../Doctor/add-doctor.php"><i class="fas fa-user-plus"></i>
                                        เพิ่มแพทย์ใหม่</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../setting.php">
                                <i class="fas fa-cog"></i> ตั้งค่า
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <?php
                require_once '../db.php';

                // ตรวจสอบว่ามีการส่ง ID มาหรือไม่
                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    header("Location: appointment.php");
                    exit();
                }

                $appointment_id = $_GET['id'];

                // ดึงข้อมูลการนัดหมาย
                try {
                    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE appointment_id = :id");
                    $stmt->execute([':id' => $appointment_id]);
                    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$appointment) {
                        throw new Exception("ไม่พบข้อมูลการนัดหมาย");
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = $e->getMessage();
                    header("Location: appointment.php");
                    exit();
                }

                // ดึงรายชื่อผู้ป่วยและแพทย์
                $patients = $pdo->query("SELECT patient_id, CONCAT(first_name, ' ', last_name) AS full_name FROM patients ORDER BY first_name")->fetchAll(PDO::FETCH_ASSOC);
                $dentists = $pdo->query("SELECT dentist_id, CONCAT(first_name, ' ', last_name) AS full_name FROM dentists ORDER BY first_name")->fetchAll(PDO::FETCH_ASSOC);

                // จัดการการอัปเดตข้อมูล
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $patient_id = $_POST['patient_id'];
                    $dentist_id = $_POST['dentist_id'];
                    $appointment_date = $_POST['appointment_date'];
                    $appointment_details = $_POST['appointment_details'];

                    try {
                        $stmt = $pdo->prepare("UPDATE appointments SET patient_id = :patient_id, dentist_id = :dentist_id, appointment_date = :appointment_date, appointment_details = :appointment_details WHERE appointment_id = :id");
                        $stmt->execute([
                            ':patient_id' => $patient_id,
                            ':dentist_id' => $dentist_id,
                            ':appointment_date' => $appointment_date,
                            ':appointment_details' => $appointment_details,
                            ':id' => $appointment_id
                        ]);
                        $_SESSION['success'] = "อัปเดตข้อมูลการนัดหมายเรียบร้อยแล้ว";
                        header("Location: appointment.php");
                        exit();
                    } catch (PDOException $e) {
                        $error = "เกิดข้อผิดพลาด: " . $e->getMessage();
                    }
                }
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">แก้ไขการนัดหมาย</h1>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">ผู้ป่วย</label>
                        <select class="form-select" id="patient_id" name="patient_id" required>
                            <option value="">เลือกผู้ป่วย</option>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['patient_id']; ?>" <?php echo $patient['patient_id'] == $appointment['patient_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($patient['full_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dentist_id" class="form-label">แพทย์</label>
                        <select class="form-select" id="dentist_id" name="dentist_id" required>
                            <option value="">เลือกแพทย์</option>
                            <?php foreach ($dentists as $dentist): ?>
                                <option value="<?php echo $dentist['dentist_id']; ?>" <?php echo $dentist['dentist_id'] == $appointment['dentist_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dentist['full_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">วันที่และเวลานัดหมาย</label>
                        <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date"
                            value="<?php echo date('Y-m-d\TH:i', strtotime($appointment['appointment_date'])); ?>"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_details" class="form-label">รายละเอียดการนัดหมาย</label>
                        <textarea class="form-control" id="appointment_details" name="appointment_details" rows="3"
                            required><?php echo htmlspecialchars($appointment['appointment_details']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    <a href="appointment.php" class="btn btn-secondary">ยกเลิก</a>
                </form>
            </main>

            <script>
                // เพิ่ม JavaScript สำหรับการตรวจสอบความถูกต้องของฟอร์ม
                (function () {
                    'use strict'
                    var forms = document.querySelectorAll('.needs-validation')
                    Array.prototype.slice.call(forms)
                        .forEach(function (form) {
                            form.addEventListener('submit', function (event) {
                                if (!form.checkValidity()) {
                                    event.preventDefault()
                                    event.stopPropagation()
                                }
                                form.classList.add('was-validated')
                            }, false)
                        })
                })()
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>