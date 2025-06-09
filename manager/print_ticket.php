<?php
require_once '../auth.php';
require_once '../functions.php';
require_once '../db.php';

checkAuth();

if (getUserRole() !== 'manager') {
    header('Location: /clinic/');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Неверный ID записи');
}

$appointment_id = (int)$_GET['id'];
$db = DB::getInstance()->getConnection();

$stmt = $db->prepare("SELECT a.id, a.appointment_date, u.full_name as doctor_name, p.encrypted_data 
                      FROM appointments a
                      JOIN users u ON a.doctor_id = u.id
                      JOIN patients p ON a.patient_id = p.id
                      WHERE a.id = ?");
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die('Запись не найдена');
}

$data = $result->fetch_assoc();
$patient_data = json_decode(DB::decrypt($data['encrypted_data']), true);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Талон на прием</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }
        h3 {
            text-align: center;
            margin-bottom: 10px;
        }
        p {
            font-size: 14pt;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .text-center {
            text-align: center;
        }
        small {
            font-size: 10pt;
        }
        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 1000);">
    <div class="text-center mb-4">
        <h3>Клиника "Здоровье"</h3>
        <p>Талон на прием</p>
    </div>
    
    <div class="mb-3">
        <p><strong>Дата приема:</strong> <?= date('d.m.Y H:i', strtotime($data['appointment_date'])) ?></p>
        <p><strong>Врач:</strong> <?= htmlspecialchars($data['doctor_name']) ?></p>
        <p><strong>Пациент:</strong> <?= htmlspecialchars($patient_data['full_name']) ?></p>
    </div>
    
    <div class="mt-4 pt-4 text-center">
        <small>Дата печати: <?= date('d.m.Y H:i') ?></small>
    </div>
</body>
</html>
