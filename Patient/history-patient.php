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

                // กำหนดจำนวนรายการต่อหน้า
                $items_per_page = 10;

                // ดึงค่าการค้นหาและหน้าปัจจุบัน
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $category = isset($_GET['category']) ? $_GET['category'] : '';
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

                // คำนวณ OFFSET สำหรับ SQL
                $offset = ($page - 1) * $items_per_page;

                // สร้างคำสั่ง SQL สำหรับดึงข้อมูลและนับจำนวนทั้งหมด
                $sql = "SELECT ph.treatment_date, p.first_name, p.last_name, p.patient_id, 
                               tt.treatment_name, ph.treatment_details, d.first_name AS doctor_first_name, d.last_name AS doctor_last_name
                        FROM patient_history ph
                        JOIN patients p ON ph.patient_id = p.patient_id
                        JOIN treatment_types tt ON ph.treatment_type_id = tt.treatment_type_id
                        JOIN dentists d ON ph.dentist_id = d.dentist_id
                        WHERE (p.first_name LIKE :search OR p.last_name LIKE :search OR p.patient_id LIKE :search)";

                $count_sql = "SELECT COUNT(*) FROM patient_history ph
                              JOIN patients p ON ph.patient_id = p.patient_id
                              JOIN treatment_types tt ON ph.treatment_type_id = tt.treatment_type_id
                              WHERE (p.first_name LIKE :search OR p.last_name LIKE :search OR p.patient_id LIKE :search)";

                if (!empty($category)) {
                    $sql .= " AND tt.treatment_name = :category";
                    $count_sql .= " AND tt.treatment_name = :category";
                }

                $sql .= " ORDER BY ph.treatment_date DESC LIMIT :limit OFFSET :offset";

                try {
                    // เตรียมและดำเนินการคำสั่ง SQL สำหรับดึงข้อมูล
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                    if (!empty($category)) {
                        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
                    }
                    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $stmt->execute();
                    $treatments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // เตรียมและดำเนินการคำสั่ง SQL สำหรับนับจำนวนทั้งหมด
                    $count_stmt = $pdo->prepare($count_sql);
                    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                    if (!empty($category)) {
                        $count_stmt->bindValue(':category', $category, PDO::PARAM_STR);
                    }
                    $count_stmt->execute();
                    $total_items = $count_stmt->fetchColumn();

                    // คำนวณจำนวนหน้าทั้งหมด
                    $total_pages = ceil($total_items / $items_per_page);

                    // ดึงรายการหมวดหมู่การรักษา
                    $category_sql = "SELECT DISTINCT treatment_name FROM treatment_types ORDER BY treatment_name";
                    $category_stmt = $pdo->query($category_sql);
                    $categories = $category_stmt->fetchAll(PDO::FETCH_COLUMN);

                } catch (PDOException $e) {
                    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
                    exit();
                }
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">ประวัติการรักษา</h1>
                    <a href="add-treatment-history.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มประวัติการรักษา
                    </a>
                </div>

                <!-- ฟอร์มค้นหา -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <form action="" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2"
                                placeholder="ค้นหาชื่อ, นามสกุล, หรือรหัสผู้ป่วย"
                                value="<?php echo htmlspecialchars($search); ?>">
                            <select name="category" class="form-select me-2">
                                <option value="">ทุกหมวดหมู่</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </form>
                    </div>
                </div>

                <!-- ตารางประวัติการรักษา -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>รหัสผู้ป่วย</th>
                                <th>การรักษา</th>
                                <th>รายละเอียด</th>
                                <th>แพทย์ผู้รักษา</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($treatments as $treatment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($treatment['treatment_date']))); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($treatment['first_name'] . ' ' . $treatment['last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($treatment['patient_id']); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['treatment_name']); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['treatment_details']); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['doctor_first_name'] . ' ' . $treatment['doctor_last_name']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- ปุ่มเพจจิเนชัน -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>"><?php echo $i; ?></a>
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