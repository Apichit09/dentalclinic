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
                                    <a class="nav-link active" href="treatment.php"><i class="fas fa-list-ul"></i>
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

                // กำหนดจำนวนรายการต่อหน้า
                $items_per_page = 10;

                // ดึงค่าการค้นหาและหน้าปัจจุบัน
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $treatment_type = isset($_GET['treatment_type']) ? $_GET['treatment_type'] : '';
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                // คำนวณ OFFSET สำหรับ SQL
                $offset = ($page - 1) * $items_per_page;

                try {
                    // ดึงรายการประเภทการรักษา
                    $type_sql = "SELECT * FROM treatment_types ORDER BY treatment_name";
                    $type_stmt = $pdo->query($type_sql);
                    $treatment_types = $type_stmt->fetchAll(PDO::FETCH_ASSOC);

                    // สร้างคำสั่ง SQL สำหรับดึงข้อมูลการรักษา
                    $sql = "SELECT ph.patient_id, ph.treatment_date, ph.treatment_type_id, ph.treatment_details, ph.dentist_id, 
                           tt.treatment_name, p.first_name AS patient_first_name, p.last_name AS patient_last_name,
                           d.first_name AS dentist_first_name, d.last_name AS dentist_last_name
                    FROM patient_history ph
                    JOIN treatment_types tt ON ph.treatment_type_id = tt.treatment_type_id
                    JOIN patients p ON ph.patient_id = p.patient_id
                    JOIN dentists d ON ph.dentist_id = d.dentist_id
                    WHERE (p.first_name LIKE :search OR p.last_name LIKE :search OR 
                          ph.treatment_details LIKE :search)";

                    $count_sql = "SELECT COUNT(*) FROM patient_history ph
                                  JOIN patients p ON ph.patient_id = p.patient_id
                                  WHERE (p.first_name LIKE :search OR p.last_name LIKE :search OR 
                                        ph.treatment_details LIKE :search)";

                    if (!empty($treatment_type)) {
                        $sql .= " AND ph.treatment_type_id = :treatment_type";
                        $count_sql .= " AND ph.treatment_type_id = :treatment_type";
                    }

                    $sql .= " ORDER BY ph.treatment_date DESC LIMIT :limit OFFSET :offset";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                    if (!empty($treatment_type)) {
                        $stmt->bindValue(':treatment_type', $treatment_type, PDO::PARAM_INT);
                    }
                    $stmt->execute();
                    $treatments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // เตรียมและดำเนินการคำสั่ง SQL สำหรับนับจำนวนทั้งหมด
                    $count_stmt = $pdo->prepare($count_sql);
                    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                    if (!empty($treatment_type)) {
                        $count_stmt->bindValue(':treatment_type', $treatment_type, PDO::PARAM_INT);
                    }
                    $count_stmt->execute();
                    $total_items = $count_stmt->fetchColumn();

                    // คำนวณจำนวนหน้าทั้งหมด
                    $total_pages = ceil($total_items / $items_per_page);

                } catch (PDOException $e) {
                    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
                    exit();
                }
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">รายการการรักษา</h1>
                    <a href="../Patient/add-treatment-history.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มการรักษาใหม่
                    </a>
                </div>

                <!-- ฟอร์มค้นหาและเลือกหมวดหมู่ -->
                <form action="" method="GET" class="mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="ค้นหาชื่อผู้ป่วยหรือรายละเอียดการรักษา"
                                value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="treatment_type" class="form-select">
                                <option value="">ทุกประเภทการรักษา</option>
                                <?php foreach ($treatment_types as $type): ?>
                                    <option value="<?php echo $type['treatment_type_id']; ?>" <?php echo $treatment_type == $type['treatment_type_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type['treatment_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>วันที่รักษา</th>
                                <th>ชื่อผู้ป่วย</th>
                                <th>ประเภทการรักษา</th>
                                <th>รายละเอียดการรักษา</th>
                                <th>แพทย์ผู้รักษา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($treatments as $treatment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($treatment['treatment_date']))); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($treatment['patient_first_name'] . ' ' . $treatment['patient_last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($treatment['treatment_name']); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['treatment_details']); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['dentist_first_name'] . ' ' . $treatment['dentist_last_name']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (empty($treatments)): ?>
                    <div class="alert alert-info" role="alert">
                        ไม่พบข้อมูลการรักษา
                    </div>
                <?php endif; ?>

                <!-- ปุ่มเพจจิเนชัน -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&treatment_type=<?php echo urlencode($treatment_type); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </main>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>