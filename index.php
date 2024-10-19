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
                        <img src="IMAGE/lOGO.jpg" alt="Dental Clinic Logo" class="img-fluid rounded-circle mb-3"
                            width="80">
                        <h5 class="text-white">คลินิกทันตกรรม</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">
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
                                    <a class="nav-link" href="Patient/add-patient.php"><i class="fas fa-plus"></i>
                                        เพิ่มผู้ป่วยใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="Patient/patient.php"><i class="fas fa-list"></i>
                                        รายชื่อผู้ป่วย</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="Patient/history-patient.php"><i
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
                                    <a class="nav-link" href="Appointment/add-appointment.php"><i
                                            class="fas fa-plus"></i> สร้างนัดหมายใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="Appointment/appointment.php"><i class="fas fa-list"></i>
                                        รายการนัดหมาย</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="Appointment/schedule.php"><i class="fas fa-clock"></i>
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
                                    <a class="nav-link" href="Treatment/treatment.php"><i class="fas fa-list-ul"></i>
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
                                    <a class="nav-link" href="Doctor/doctor.php"><i class="fas fa-list"></i>
                                        รายชื่อแพทย์</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="Doctor/add-doctor.php"><i class="fas fa-user-plus"></i>
                                        เพิ่มแพทย์ใหม่</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="setting.php">
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
                    <h1 class="h2">แดชบอร์ด Dental Clinic</h1>
                </div>

                <?php
                require_once 'db.php';

                // ดึงข้อมูลสถิติ
                $today = date('Y-m-d');
                $month_start = date('Y-m-01');

                // จำนวนผู้ป่วยวันนี้
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = :today");
                $stmt->execute([':today' => $today]);
                $patients_today = $stmt->fetchColumn();

                // นัดหมายที่รอดำเนินการ
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_date > :now");
                $stmt->execute([':now' => date('Y-m-d H:i:s')]);
                $pending_appointments = $stmt->fetchColumn();

                // ผู้ป่วยรายใหม่เดือนนี้
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM patients WHERE registration_date >= :month_start");
                $stmt->execute([':month_start' => $month_start]);
                $new_patients_this_month = $stmt->fetchColumn();

                // คนที่ใกล้ถึงคิวนัด (ภายใน 1 ชั่วโมง)
                $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM appointments 
        WHERE appointment_date BETWEEN :now AND DATE_ADD(:now, INTERVAL 1 HOUR)
    ");
                $stmt->execute([':now' => date('Y-m-d H:i:s')]);
                $upcoming_appointments = $stmt->fetchColumn();

                // ดึงข้อมูลนัดหมายวันนี้
                $stmt = $pdo->prepare("
        SELECT a.appointment_date, p.first_name AS patient_first_name, p.last_name AS patient_last_name,
               d.first_name AS dentist_first_name, d.last_name AS dentist_last_name,
               a.appointment_details
        FROM appointments a
        JOIN patients p ON a.patient_id = p.patient_id
        JOIN dentists d ON a.dentist_id = d.dentist_id
        WHERE DATE(a.appointment_date) = :today
        ORDER BY a.appointment_date ASC
    ");
                $stmt->execute([':today' => $today]);
                $today_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // ดึงข้อมูลการรักษาล่าสุด
                $stmt = $pdo->prepare("
        SELECT ph.treatment_date, p.first_name AS patient_first_name, p.last_name AS patient_last_name,
               tt.treatment_name, d.first_name AS dentist_first_name, d.last_name AS dentist_last_name
        FROM patient_history ph
        JOIN patients p ON ph.patient_id = p.patient_id
        JOIN treatment_types tt ON ph.treatment_type_id = tt.treatment_type_id
        JOIN dentists d ON ph.dentist_id = d.dentist_id
        ORDER BY ph.treatment_date DESC
        LIMIT 5
    ");
                $stmt->execute();
                $recent_treatments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // ดึงข้อมูลสัดส่วนการรักษา
                $stmt = $pdo->prepare("
        SELECT tt.treatment_name, COUNT(*) as count
        FROM patient_history ph
        JOIN treatment_types tt ON ph.treatment_type_id = tt.treatment_type_id
        GROUP BY tt.treatment_type_id
        ORDER BY count DESC
        LIMIT 5
    ");
                $stmt->execute();
                $treatment_proportions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // ดึงข้อมูลคนไข้รายใหม่ 7 วันล่าสุด
                $stmt = $pdo->prepare("
        SELECT DATE(registration_date) as date, COUNT(*) as count
        FROM patients
        WHERE registration_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(registration_date)
        ORDER BY date ASC
    ");
                $stmt->execute();
                $new_patients_last_7_days = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <!-- สรุปข้อมูลสำคัญ -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">จำนวนผู้ป่วยวันนี้</h5>
                                <p class="card-text display-4"><?php echo $patients_today; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">นัดหมายที่รอดำเนินการ</h5>
                                <p class="card-text display-4"><?php echo $pending_appointments; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">ผู้ป่วยรายใหม่เดือนนี้</h5>
                                <p class="card-text display-4"><?php echo $new_patients_this_month; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">คนที่ใกล้ถึงคิวนัด (1 ชม.)</h5>
                                <p class="card-text display-4"><?php echo $upcoming_appointments; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- กราฟแสดงข้อมูล -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h2>สัดส่วนการรักษา</h2>
                        <div id="treatmentChart" style="width:100%; height:300px;"></div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h2>คนไข้รายใหม่ (7 วันล่าสุด)</h2>
                        <div id="newPatientChart" style="width:100%; height:300px;"></div>
                    </div>
                </div>

                <!-- ตารางนัดหมายวันนี้ -->
                <h2 class="mt-4">นัดหมายวันนี้</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>เวลา</th>
                                <th>ชื่อผู้ป่วย</th>
                                <th>แพทย์</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($today_appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo date('H:i', strtotime($appointment['appointment_date'])); ?></td>
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

                <!-- การรักษาล่าสุด -->
                <h2 class="mt-4">การรักษาล่าสุด</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>วันที่</th>
                                <th>ผู้ป่วย</th>
                                <th>การรักษา</th>
                                <th>แพทย์</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_treatments as $treatment): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($treatment['treatment_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['patient_first_name'] . ' ' . $treatment['patient_last_name']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($treatment['treatment_name']); ?></td>
                                    <td><?php echo htmlspecialchars($treatment['dentist_first_name'] . ' ' . $treatment['dentist_last_name']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>

            <!-- เพิ่ม Google Charts -->
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', { 'packages': ['corechart'] });
                google.charts.setOnLoadCallback(drawCharts);

                function drawCharts() {
                    // กราฟวงกลมแสดงสัดส่วนการรักษา
                    var treatmentData = google.visualization.arrayToDataTable([
                        ['การรักษา', 'จำนวน'],
                        <?php
                        foreach ($treatment_proportions as $treatment) {
                            echo "['" . $treatment['treatment_name'] . "', " . $treatment['count'] . "],";
                        }
                        ?>
                    ]);

                    var treatmentOptions = {
                        title: 'สัดส่วนการรักษา',
                        is3D: true,
                    };

                    var treatmentChart = new google.visualization.PieChart(document.getElementById('treatmentChart'));
                    treatmentChart.draw(treatmentData, treatmentOptions);

                    // กราฟเส้นแสดงข้อมูลคนไข้รายใหม่
                    var newPatientData = google.visualization.arrayToDataTable([
                        ['วันที่', 'จำนวนคนไข้ใหม่'],
                        <?php
                        foreach ($new_patients_last_7_days as $day) {
                            echo "[new Date('" . $day['date'] . "'), " . $day['count'] . "],";
                        }
                        ?>
                    ]);

                    var newPatientOptions = {
                        title: 'คนไข้รายใหม่ (7 วันล่าสุด)',
                        curveType: 'function',
                        legend: { position: 'bottom' },
                        hAxis: {
                            format: 'd/M',
                            gridlines: { count: 7 }
                        }
                    };

                    var newPatientChart = new google.visualization.LineChart(document.getElementById('newPatientChart'));
                    newPatientChart.draw(newPatientData, newPatientOptions);
                }
            </script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>