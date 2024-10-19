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
                            <a class="nav-link" href="index.php">
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
                            <a class="nav-link active" href="setting.php">
                                <i class="fas fa-cog"></i> ตั้งค่า
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-4">
                    <h2 class="mb-4">ตั้งค่าระบบ</h2>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">ข้อมูลคลินิก</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="clinicName" class="form-label">ชื่อคลินิก</label>
                                            <input type="text" class="form-control" id="clinicName" name="clinicName">
                                        </div>
                                        <div class="mb-3">
                                            <label for="clinicAddress" class="form-label">ที่อยู่</label>
                                            <textarea class="form-control" id="clinicAddress" name="clinicAddress"
                                                rows="3"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="clinicPhone" class="form-label">เบอร์โทรศัพท์</label>
                                            <input type="tel" class="form-control" id="clinicPhone" name="clinicPhone">
                                        </div>
                                        <div class="mb-3">
                                            <label for="clinicEmail" class="form-label">อีเมล</label>
                                            <input type="email" class="form-control" id="clinicEmail"
                                                name="clinicEmail">
                                        </div>
                                        <button type="submit" class="btn btn-primary">บันทึกข้อมูลคลินิก</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">การแจ้งเตือน</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="emailNotification"
                                                name="emailNotification">
                                            <label class="form-check-label"
                                                for="emailNotification">เปิดใช้งานการแจ้งเตือนทางอีเมล</label>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="smsNotification"
                                                name="smsNotification">
                                            <label class="form-check-label"
                                                for="smsNotification">เปิดใช้งานการแจ้งเตือนทาง SMS</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="reminderTime" class="form-label">เวลาแจ้งเตือนล่วงหน้า
                                                (ชั่วโมง)</label>
                                            <input type="number" class="form-control" id="reminderTime"
                                                name="reminderTime" min="1" max="72">
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary">บันทึกการตั้งค่าการแจ้งเตือน</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">การสำรองข้อมูล</h5>
                                </div>
                                <div class="card-body">
                                    <p>การสำรองข้อมูลล่าสุด: <span id="lastBackup">ไม่มีข้อมูล</span></p>
                                    <button type="button" class="btn btn-primary"
                                        id="backupNow">สำรองข้อมูลทันที</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>