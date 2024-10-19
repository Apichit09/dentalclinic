<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic Dashboard</title>
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

                // ฟังก์ชันสำหรับค้นหาผู้ป่วย
                function searchPatients($search = '')
                {
                    global $pdo;
                    $sql = "SELECT * FROM patients WHERE 
                            first_name LIKE :search OR 
                            last_name LIKE :search OR 
                            phone_number LIKE :search OR 
                            email LIKE :search
                            ORDER BY last_name, first_name";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':search' => "%$search%"]);
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $patients = searchPatients($search);
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">รายชื่อผู้ป่วย</h1>
                </div>

                <!-- ส่วนค้นหา -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2"
                                placeholder="ค้นหาชื่อ, นามสกุล, เบอร์โทร, อีเมล"
                                value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="add-patient.php" class="btn btn-success">เพิ่มผู้ป่วยใหม่</a>
                    </div>
                </div>

                <!-- ตารางแสดงข้อมูลผู้ป่วย -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>วันเกิด</th>
                                <th>เพศ</th>
                                <th>เบอร์โทร</th>
                                <th>อีเมล</th>
                                <th>วันที่ลงทะเบียน</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $patient): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($patient['patient_id']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($patient['date_of_birth']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['email']); ?></td>
                                    <td><?php echo htmlspecialchars($patient['registration_date']); ?></td>
                                    <td>
                                        <a href="view-patient.php?id=<?php echo $patient['patient_id']; ?>"
                                            class="btn btn-sm btn-info">ดู</a>
                                        <a href="edit-patient.php?id=<?php echo $patient['patient_id']; ?>"
                                            class="btn btn-sm btn-warning">แก้ไข</a>
                                        <a href="delete-patient.php?id=<?php echo $patient['patient_id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลผู้ป่วยนี้?')">ลบ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($patients)): ?>
                    <div class="alert alert-info" role="alert">
                        ไม่พบข้อมูลผู้ป่วย
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>