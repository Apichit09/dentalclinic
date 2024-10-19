<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้ป่วย - Dental Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/styles.css">
    <link rel="icon" href="../IMAGE/logo.jpg" type="image/jpeg">
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
                                    <a class="nav-link" href="add-patient.php"><i class="fas fa-plus"></i>
                                        เพิ่มผู้ป่วยใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link active" href="patient.php"><i class="fas fa-list"></i>
                                        รายชื่อผู้ป่วย</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="history-patient.php"><i class="fas fa-history"></i>
                                        ประวัติการรักษา</a>
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
                                    <a class="nav-link" href="../Appointment/add-appointment.php"><i
                                            class="fas fa-plus"></i> สร้างนัดหมายใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="../Appointment/appointment.php"><i
                                            class="fas fa-list"></i> รายการนัดหมาย</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="../Appointment/schedule.php"><i class="fas fa-clock"></i>
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

                if (!isset($_GET['id']) || empty($_GET['id'])) {
                    echo "<div class='alert alert-danger' role='alert'>ไม่ได้ระบุ ID ของผู้ป่วย</div>";
                    exit;
                }

                $patient_id = $_GET['id'];

                try {
                    // ดึงข้อมูลผู้ป่วย
                    $patient_sql = "SELECT * FROM patients WHERE patient_id = :patient_id";
                    $patient_stmt = $pdo->prepare($patient_sql);
                    $patient_stmt->execute([':patient_id' => $patient_id]);
                    $patient = $patient_stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$patient) {
                        throw new Exception("ไม่พบข้อมูลผู้ป่วย");
                    }

                    // ดึงประวัติการรักษาของผู้ป่วย
                    $history_sql = "SELECT ph.*, tt.treatment_name, d.first_name AS dentist_first_name, d.last_name AS dentist_last_name
                        FROM patient_history ph
                        JOIN treatment_types tt ON ph.treatment_type_id = tt.treatment_type_id
                        JOIN dentists d ON ph.dentist_id = d.dentist_id
                        WHERE ph.patient_id = :patient_id
                        ORDER BY ph.treatment_date DESC";
                    $history_stmt = $pdo->prepare($history_sql);
                    $history_stmt->execute([':patient_id' => $patient_id]);
                    $treatment_history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);

                    // ดึงข้อมูลทันตแพทย์ที่เคยรักษาผู้ป่วยรายนี้
                    $dentist_sql = "SELECT DISTINCT d.*
                        FROM dentists d
                        JOIN patient_history ph ON d.dentist_id = ph.dentist_id
                        WHERE ph.patient_id = :patient_id";
                    $dentist_stmt = $pdo->prepare($dentist_sql);
                    $dentist_stmt->execute([':patient_id' => $patient_id]);
                    $dentists = $dentist_stmt->fetchAll(PDO::FETCH_ASSOC);

                } catch (Exception $e) {
                    echo "<div class='alert alert-danger' role='alert'>เกิดข้อผิดพลาด: " . $e->getMessage() . "</div>";
                    exit;
                }
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">ข้อมูลผู้ป่วย</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="patient.php" class="btn btn-secondary me-2">กลับไปหน้ารายชื่อผู้ป่วย</a>
                        <a href="edit-patient.php?id=<?php echo $patient_id; ?>" class="btn btn-primary">แก้ไขข้อมูล</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h3>ข้อมูลผู้ป่วย</h3>
                        <table class="table">
                            <?php foreach ($patient as $key => $value): ?>
                                <tr>
                                    <th><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $key))); ?>:</th>
                                    <td><?php echo htmlspecialchars($value); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <?php if (!empty($dentists)): ?>
                        <div class="col-md-6">
                            <h3>ข้อมูลทันตแพทย์ที่เคยรักษา</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>อีเมล</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dentists as $dentist): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($dentist['first_name'] . ' ' . $dentist['last_name']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($dentist['email']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($treatment_history)): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h3>ประวัติการรักษา</h3>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>วันที่</th>
                                        <th>ประเภทการรักษา</th>
                                        <th>รายละเอียด</th>
                                        <th>ทันตแพทย์</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($treatment_history as $history): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($history['treatment_date']))); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($history['treatment_name']); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($history['treatment_details'])); ?></td>
                                            <td><?php echo htmlspecialchars($history['dentist_first_name'] . ' ' . $history['dentist_last_name']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-4" role="alert">
                        ไม่พบประวัติการรักษาสำหรับผู้ป่วยนี้
                    </div>
                <?php endif; ?>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>