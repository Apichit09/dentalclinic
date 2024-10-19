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
                                    <a class="nav-link active" href="doctor.php"><i class="fas fa-list"></i>
                                        รายชื่อแพทย์</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="add-doctor.php"><i class="fas fa-user-plus"></i>
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
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">รายชื่อแพทย์</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="add-doctor.php" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> เพิ่มแพทย์ใหม่
                        </a>
                    </div>
                </div>

                <?php
                require_once '../db.php';

                $search = isset($_GET['search']) ? $_GET['search'] : '';

                try {
                    $sql = "SELECT * FROM dentists WHERE 
                first_name LIKE :search OR 
                last_name LIKE :search OR 
                license_number LIKE :search OR 
                specialization LIKE :search 
                ORDER BY last_name, first_name";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':search' => "%$search%"]);
                    $dentists = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
                    exit();
                }
                ?>

                <!-- ฟอร์มค้นหา -->
                <form action="" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ค้นหาชื่อ, นามสกุล, เลขที่ใบอนุญาต หรือความเชี่ยวชาญ"
                            value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ชื่อ-นามสกุล</th>
                                <th>วันเกิด</th>
                                <th>เลขที่ใบอนุญาต</th>
                                <th>ความเชี่ยวชาญ</th>
                                <th>เบอร์โทรศัพท์</th>
                                <th>อีเมล</th>
                                <th>ที่อยู่</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dentists as $dentist): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($dentist['first_name'] . ' ' . $dentist['last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($dentist['date_of_birth']); ?></td>
                                    <td><?php echo htmlspecialchars($dentist['license_number']); ?></td>
                                    <td><?php echo htmlspecialchars($dentist['specialization']); ?></td>
                                    <td><?php echo htmlspecialchars($dentist['phone_number']); ?></td>
                                    <td><?php echo htmlspecialchars($dentist['email']); ?></td>
                                    <td><?php echo htmlspecialchars($dentist['address']); ?></td>
                                    <td>
                                        <a href="edit-doctor.php?id=<?php echo $dentist['dentist_id']; ?>"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> แก้ไข
                                        </a>
                                        <a href="delete-doctor.php?id=<?php echo $dentist['dentist_id']; ?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบแพทย์ท่านนี้?')">
                                            <i class="fas fa-trash"></i> ลบ
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($dentists)): ?>
                    <div class="alert alert-info" role="alert">
                        ไม่พบข้อมูลแพทย์
                    </div>
                <?php endif; ?>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>