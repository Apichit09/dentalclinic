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

                $current_date = date('Y-m-d H:i:s');
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $per_page = 10;
                $offset = ($page - 1) * $per_page;

                // ฟังก์ชันสำหรับดึงข้อมูลนัดหมาย
                function getAppointments($pdo, $condition, $search, $offset, $per_page)
                {
                    $current_date = date('Y-m-d H:i:s');

                    $sql = "SELECT a.*, p.first_name AS patient_first_name, p.last_name AS patient_last_name, 
                       d.first_name AS dentist_first_name, d.last_name AS dentist_last_name
                FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                JOIN dentists d ON a.dentist_id = d.dentist_id
                WHERE $condition
                AND (p.first_name LIKE :search OR p.last_name LIKE :search OR
                     d.first_name LIKE :search OR d.last_name LIKE :search OR
                     a.appointment_details LIKE :search)
                ORDER BY a.appointment_date " . ($condition == "a.appointment_date > :current_date" ? "ASC" : "DESC") . "
                LIMIT :offset, :per_page";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':current_date', $current_date, PDO::PARAM_STR);
                    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
                    $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
                    $stmt->execute();
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }

                // ฟังก์ชันสำหรับนับจำนวนนัดหมายทั้งหมด
                function countAppointments($pdo, $condition, $search, $current_date)
                {
                    $sql = "SELECT COUNT(*) FROM appointments a
                JOIN patients p ON a.patient_id = p.patient_id
                JOIN dentists d ON a.dentist_id = d.dentist_id
                WHERE $condition
                AND (p.first_name LIKE :search OR p.last_name LIKE :search OR
                     d.first_name LIKE :search OR d.last_name LIKE :search OR
                     a.appointment_details LIKE :search)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':current_date', $current_date, PDO::PARAM_STR);
                    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
                    $stmt->execute();
                    return $stmt->fetchColumn();
                }

                $upcoming_appointments = getAppointments($pdo, "a.appointment_date > :current_date", $search, $offset, $per_page);
                $past_appointments = getAppointments($pdo, "a.appointment_date <= :current_date", $search, $offset, $per_page);

                $total_upcoming = countAppointments($pdo, "a.appointment_date > :current_date", $search, $current_date);
                $total_past = countAppointments($pdo, "a.appointment_date <= :current_date", $search, $current_date);

                $total_pages_upcoming = ceil($total_upcoming / $per_page);
                $total_pages_past = ceil($total_past / $per_page);
                ?>

                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">รายการนัดหมาย</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="add-appointment.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-plus"></i> เพิ่มนัดหมายใหม่
                        </a>
                    </div>
                </div>

                <!-- ฟอร์มค้นหา -->
                <form action="" method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ค้นหาชื่อผู้ป่วย, แพทย์ หรือรายละเอียดการนัดหมาย"
                            value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn btn-primary">ค้นหา</button>
                    </div>
                </form>

                <h3>นัดหมายที่กำลังจะมาถึง</h3>
                <?php if (empty($upcoming_appointments)): ?>
                    <p>ไม่มีนัดหมายที่กำลังจะมาถึง</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>วันที่และเวลา</th>
                                    <th>ผู้ป่วย</th>
                                    <th>แพทย์</th>
                                    <th>รายละเอียด</th>
                                    <th>การดำเนินการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($upcoming_appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($appointment['appointment_date']))); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['dentist_first_name'] . ' ' . $appointment['dentist_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_details']); ?></td>
                                        <td>
                                            <a href="edit-appointment.php?id=<?php echo $appointment['appointment_id']; ?>"
                                                class="btn btn-sm btn-primary">แก้ไข</a>
                                            <a href="delete-appointment.php?id=<?php echo $appointment['appointment_id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบนัดหมายนี้?')">ลบ</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for upcoming appointments -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages_upcoming; $i++): ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                    <a class="page-link"
                                        href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>

                <h3 class="mt-5">นัดหมายที่ผ่านมาแล้ว</h3>
                <?php if (empty($past_appointments)): ?>
                    <p>ไม่มีนัดหมายที่ผ่านมาแล้ว</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>วันที่และเวลา</th>
                                    <th>ผู้ป่วย</th>
                                    <th>แพทย์</th>
                                    <th>รายละเอียด</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($past_appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($appointment['appointment_date']))); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['patient_first_name'] . ' ' . $appointment['patient_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['dentist_first_name'] . ' ' . $appointment['dentist_last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_details']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination for past appointments -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages_past; $i++): ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                    <a class="page-link"
                                        href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </main>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>