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
                                    <a class="nav-link active" href="add-appointment.php"><i class="fas fa-plus"></i>
                                        สร้างนัดหมายใหม่</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="appointment.php"><i class="fas fa-list"></i>
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
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">สร้างนัดหมายใหม่</h1>
                </div>

                <?php
                require_once '../db.php';

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $patient_id = $_POST['patient_id'];
                    $dentist_id = $_POST['dentist_id'];
                    $appointment_date = $_POST['appointment_date'];
                    $appointment_details = $_POST['appointment_details'];

                    try {
                        $sql = "INSERT INTO appointments (patient_id, dentist_id, appointment_date, appointment_details) 
                    VALUES (:patient_id, :dentist_id, :appointment_date, :appointment_details)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([
                            ':patient_id' => $patient_id,
                            ':dentist_id' => $dentist_id,
                            ':appointment_date' => $appointment_date,
                            ':appointment_details' => $appointment_details
                        ]);
                        echo "<div class='alert alert-success' role='alert'>เพิ่มนัดหมายสำเร็จ</div>";
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger' role='alert'>เกิดข้อผิดพลาด: " . $e->getMessage() . "</div>";
                    }
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
                    class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="patient_search" class="form-label">ค้นหาผู้ป่วย</label>
                        <input type="text" class="form-control" id="patient_search" placeholder="พิมพ์ชื่อผู้ป่วย">
                        <select class="form-select mt-2" id="patient_id" name="patient_id" required>
                            <option value="">เลือกผู้ป่วย</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dentist_search" class="form-label">ค้นหาแพทย์</label>
                        <input type="text" class="form-control" id="dentist_search" placeholder="พิมพ์ชื่อแพทย์">
                        <select class="form-select mt-2" id="dentist_id" name="dentist_id" required>
                            <option value="">เลือกแพทย์</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">วันที่และเวลานัดหมาย</label>
                        <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_details" class="form-label">รายละเอียดการนัดหมาย</label>
                        <textarea class="form-control" id="appointment_details" name="appointment_details" rows="3"
                            required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกนัดหมาย</button>
                </form>
            </main>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function () {
                    function searchPeople(searchTerm, type) {
                        $.ajax({
                            url: 'search_people.php',
                            method: 'POST',
                            data: { search: searchTerm, type: type },
                            dataType: 'json',
                            success: function (response) {
                                var select = $('#' + type + '_id');
                                select.empty();
                                select.append('<option value="">เลือก' + (type === 'patient' ? 'ผู้ป่วย' : 'แพทย์') + '</option>');
                                $.each(response, function (index, item) {
                                    select.append('<option value="' + item.id + '">' + item.name + '</option>');
                                });
                            }
                        });
                    }

                    $('#patient_search').on('input', function () {
                        searchPeople($(this).val(), 'patient');
                    });

                    $('#dentist_search').on('input', function () {
                        searchPeople($(this).val(), 'dentist');
                    });
                });

                // เพิ่ม JavaScript สำหรับการตรวจสอบความถูกต้องของฟอร์ม
                (function () {
                    'use strict'
                    var forms = document.querySelectorAll('.needs-validation')
                    Array.prototype.slice.call(forms)
                        .forEach(function (form) {
                            form.addEventListener('submit', function (event) {
                                if (!form.checkValidity()) {
                                    event.preventDefault()
                                    event.stopPropagation()
                                }
                                form.classList.add('was-validated')
                            }, false)
                        })
                })()
            </script>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>