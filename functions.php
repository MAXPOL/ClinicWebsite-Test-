<?php
session_start();
require_once 'db.php';

function getAllDoctors() {
    $db = DB::getInstance()->getConnection();
    $result = $db->query("SELECT id, full_name FROM users WHERE role = 'doctor'");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function createPatient($data) {
    $db = DB::getInstance()->getConnection();
    $encrypted_data = DB::encrypt(json_encode($data));
    
    $stmt = $db->prepare("INSERT INTO patients (encrypted_data) VALUES (?)");
    $stmt->bind_param("s", $encrypted_data);
    $stmt->execute();
    
    return $db->insert_id;
}

function createAppointment($patient_id, $doctor_id, $appointment_date) {
    $db = DB::getInstance()->getConnection();
    
    $stmt = $db->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $patient_id, $doctor_id, $appointment_date);
    return $stmt->execute();
}

function getAppointmentsForManager() {
    $db = DB::getInstance()->getConnection();
    $query = "SELECT a.id, a.appointment_date, u.full_name as doctor_name 
              FROM appointments a
              JOIN users u ON a.doctor_id = u.id
              ORDER BY a.appointment_date DESC";
    $result = $db->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAppointmentsForDoctor($doctor_id) {
    $db = DB::getInstance()->getConnection();
    
    $stmt = $db->prepare("SELECT a.id, a.appointment_date, a.diagnosis, a.recommendations 
                          FROM appointments a
                          WHERE a.doctor_id = ? AND a.appointment_date <= NOW()
                          ORDER BY a.appointment_date DESC");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateDiagnosis($appointment_id, $diagnosis, $recommendations) {
    $db = DB::getInstance()->getConnection();
    
    $stmt = $db->prepare("UPDATE appointments SET diagnosis = ?, recommendations = ? WHERE id = ?");
    $stmt->bind_param("ssi", $diagnosis, $recommendations, $appointment_id);
    return $stmt->execute();
}
