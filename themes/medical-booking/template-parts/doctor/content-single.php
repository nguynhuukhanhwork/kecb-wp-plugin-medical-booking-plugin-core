<?php

use MedicalBooking\Infrastructure\Repository\DoctorRepository;

$doctor_id = get_the_id();
$doctor = DoctorRepository::findDoctorById($doctor_id);
?>

<div class="doctor-card">
    <h1>demo 113</h1>
</div>
