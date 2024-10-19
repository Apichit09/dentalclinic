<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ป่วย - Dental Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/styles.css">
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
                    header("Location: patients.php");
                    exit;
                }

                $patient_id = $_GET['id'];

                // ดึงข้อมูลผู้ป่วย
                try {
                    $stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = :id");
                    $stmt->execute([':id' => $patient_id]);
                    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$patient) {
                        throw new Exception("ไม่พบข้อมูลผู้ป่วย");
                    }
                } catch (Exception $e) {
                    $error_message = $e->getMessage();
                }

                // จัดการการอัปเดตข้อมูล
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $first_name = $_POST['first_name'];
                    $last_name = $_POST['last_name'];
                    $date_of_birth = $_POST['date_of_birth'];
                    $gender = $_POST['gender'];
                    $phone_number = $_POST['phone_number'];
                    $email = $_POST['email'];
                    $address = $_POST['address'];
                    $allergies = $_POST['allergies'];
                    $medical_conditions = $_POST['medical_conditions'];

                    try {
                        $sql = "UPDATE patients SET 
                    first_name = :first_name, 
                    last_name = :last_name, 
                    date_of_birth = :date_of_birth, 
                    gender = :gender, 
                    phone_number = :phone_number, 
                    email = :email, 
                    address = :address, 
                    allergies = :allergies, 
                    medical_conditions = :medical_conditions 
                    WHERE patient_id = :id";

                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':first_name' => $first_name,
                            ':last_name' => $last_name,
                            ':date_of_birth' => $date_of_birth,
                            ':gender' => $gender,
                            ':phone_number' => $phone_number,
                            ':email' => $email,
                            ':address' => $address,
                            ':allergies' => $allergies,
                            ':medical_conditions' => $medical_conditions,
                            ':id' => $patient_id
                        ]);

                        $success_message = "อัปเดตข้อมูลผู้ป่วยเรียบร้อยแล้ว";

                        // อัปเดตข้อมูลในตัวแปร $patient
                        $patient = [
                            'patient_id' => $patient_id,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'date_of_birth' => $date_of_birth,
                            'gender' => $gender,
                            'phone_number' => $phone_number,
                            'email' => $email,
                            'address' => $address,
                            'allergies' => $allergies,
                            'medical_conditions' => $medical_conditions,
                            'registration_date' => $patient['registration_date'],
                            'last_visit_date' => $patient['last_visit_date']
                        ];
                    } catch (PDOException $e) {
                        $error_message = "เกิดข้อผิดพลาด: " . $e->getMessage();
                    }
                }
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">แก้ไขข้อมูลผู้ป่วย</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="view-patient.php?id=<?php echo $patient_id; ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> กลับไปหน้าข้อมูลผู้ป่วย
                        </a>
                    </div>
                </div>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $patient_id); ?>"
                    method="post" class="patient-form">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">นามสกุล</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date_of_birth" class="form-label">วันเกิด</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">เพศ</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="ชาย" <?php echo $patient['gender'] == 'ชาย' ? 'selected' : ''; ?>>ชาย
                                </option>
                                <option value="หญิง" <?php echo $patient['gender'] == 'หญิง' ? 'selected' : ''; ?>>หญิง
                                </option>
                                <option value="อื่นๆ" <?php echo $patient['gender'] == 'อื่นๆ' ? 'selected' : ''; ?>>อื่นๆ
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number"
                            value="<?php echo htmlspecialchars($patient['phone_number']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">อีเมล</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($patient['email']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">ที่อยู่</label>
                        <textarea class="form-control" id="address" name="address"
                            rows="3"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="allergies" class="form-label">ประวัติการแพ้</label>
                        <textarea class="form-control" id="allergies" name="allergies"
                            rows="2"><?php echo htmlspecialchars($patient['allergies']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="medical_conditions" class="form-label">โรคประจำตัว</label>
                        <textarea class="form-control" id="medical_conditions" name="medical_conditions"
                            rows="2"><?php echo htmlspecialchars($patient['medical_conditions']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                    </button>
                </form>
            </main>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>