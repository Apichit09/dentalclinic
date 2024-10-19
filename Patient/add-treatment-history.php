<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มประวัติการรักษา - Dental Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles.css">\
    <link rel="icon" href="IMAGE/logoTab.jpg" type="image/x-icon">
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
                                    <a class="nav-link" href="patient.php"><i class="fas fa-list"></i>
                                        รายชื่อผู้ป่วย</a>
                                </li>
                                <li>
                                    <a class="nav-link active" href="history-patient.php"><i class="fas fa-history"></i>
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

                $success_message = '';
                $error_message = '';

                // ดึงรายชื่อผู้ป่วย
                $patient_sql = "SELECT patient_id, CONCAT(first_name, ' ', last_name) AS patient_name FROM patients ORDER BY first_name, last_name";
                $patient_stmt = $pdo->query($patient_sql);
                $patients = $patient_stmt->fetchAll(PDO::FETCH_ASSOC);

                // ดึงรายชื่อทันตแพทย์
                $dentist_sql = "SELECT dentist_id, CONCAT(first_name, ' ', last_name) AS dentist_name FROM dentists ORDER BY first_name, last_name";
                $dentist_stmt = $pdo->query($dentist_sql);
                $dentists = $dentist_stmt->fetchAll(PDO::FETCH_ASSOC);

                // ดึงประเภทการรักษา
                function fetchTreatmentTypes($pdo)
                {
                    $treatment_type_sql = "SELECT treatment_type_id, treatment_name, description FROM treatment_types ORDER BY treatment_name";
                    $treatment_type_stmt = $pdo->query($treatment_type_sql);
                    return $treatment_type_stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                $treatment_types = fetchTreatmentTypes($pdo);

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['add_treatment_type'])) {
                        // เพิ่มประเภทการรักษาใหม่
                        $new_treatment_type = $_POST['new_treatment_type'];
                        $new_treatment_description = $_POST['new_treatment_description'];
                        try {
                            $sql = "INSERT INTO treatment_types (treatment_name, description) VALUES (:treatment_name, :description)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':treatment_name' => $new_treatment_type,
                                ':description' => $new_treatment_description
                            ]);
                            $success_message = "เพิ่มประเภทการรักษาใหม่สำเร็จ";
                            $treatment_types = fetchTreatmentTypes($pdo);
                        } catch (PDOException $e) {
                            $error_message = "เกิดข้อผิดพลาดในการเพิ่มประเภทการรักษา: " . $e->getMessage();
                        }
                    } else {
                        // เพิ่มประวัติการรักษา
                        $patient_id = $_POST['patient_id'];
                        $treatment_date = $_POST['treatment_date'];
                        $treatment_type_id = $_POST['treatment_type_id'];
                        $treatment_details = $_POST['treatment_details'];
                        $dentist_id = $_POST['dentist_id'];

                        try {
                            $sql = "INSERT INTO patient_history (patient_id, treatment_date, treatment_type_id, treatment_details, dentist_id) 
                        VALUES (:patient_id, :treatment_date, :treatment_type_id, :treatment_details, :dentist_id)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([
                                ':patient_id' => $patient_id,
                                ':treatment_date' => $treatment_date,
                                ':treatment_type_id' => $treatment_type_id,
                                ':treatment_details' => $treatment_details,
                                ':dentist_id' => $dentist_id
                            ]);

                            $success_message = "เพิ่มประวัติการรักษาสำเร็จ";
                        } catch (PDOException $e) {
                            $error_message = "เกิดข้อผิดพลาด: " . $e->getMessage();
                        }
                    }
                }
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">เพิ่มประวัติการรักษา</h1>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="patient_id" class="form-label">ผู้ป่วย</label>
                        <select class="form-select" id="patient_id" name="patient_id" required>
                            <option value="">เลือกผู้ป่วย</option>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?php echo $patient['patient_id']; ?>">
                                    <?php echo htmlspecialchars($patient['patient_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="treatment_date" class="form-label">วันที่รักษา</label>
                        <input type="date" class="form-control" id="treatment_date" name="treatment_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="treatment_type_id" class="form-label">ประเภทการรักษา</label>
                        <div class="input-group">
                            <select class="form-select" id="treatment_type_id" name="treatment_type_id" required>
                                <option value="">เลือกประเภทการรักษา</option>
                                <?php foreach ($treatment_types as $type): ?>
                                    <option value="<?php echo $type['treatment_type_id']; ?>">
                                        <?php echo htmlspecialchars($type['treatment_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#addTreatmentTypeModal">
                                <i class="fas fa-plus"></i> เพิ่มประเภท
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="treatment_details" class="form-label">รายละเอียดการรักษา</label>
                        <textarea class="form-control" id="treatment_details" name="treatment_details" rows="3"
                            required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="dentist_id" class="form-label">ทันตแพทย์</label>
                        <select class="form-select" id="dentist_id" name="dentist_id" required>
                            <option value="">เลือกทันตแพทย์</option>
                            <?php foreach ($dentists as $dentist): ?>
                                <option value="<?php echo $dentist['dentist_id']; ?>">
                                    <?php echo htmlspecialchars($dentist['dentist_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">บันทึกประวัติการรักษา</button>
                </form>

                <!-- Modal สำหรับเพิ่มประเภทการรักษา -->
                <div class="modal fade" id="addTreatmentTypeModal" tabindex="-1"
                    aria-labelledby="addTreatmentTypeModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addTreatmentTypeModalLabel">เพิ่มประเภทการรักษาใหม่</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <div class="mb-3">
                                        <label for="new_treatment_type" class="form-label">ชื่อประเภทการรักษา</label>
                                        <input type="text" class="form-control" id="new_treatment_type"
                                            name="new_treatment_type" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_treatment_description"
                                            class="form-label">คำอธิบายประเภทการรักษา</label>
                                        <textarea class="form-control" id="new_treatment_description"
                                            name="new_treatment_description" rows="3"></textarea>
                                    </div>
                                    <button type="submit" name="add_treatment_type"
                                        class="btn btn-primary">เพิ่มประเภทการรักษา</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>