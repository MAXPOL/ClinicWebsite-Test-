<?php
session_start();
require_once '../header.php';
require_once '../auth.php';
require_once '../functions.php';

checkAuth();

if (getUserRole() !== 'manager') {
    header('Location: /clinic/');
    exit();
}

// Обработка создания записи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['full_name'])) {
    $patient_data = [
        'full_name' => $_POST['full_name'],
        'birth_date' => $_POST['birth_date'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address']
    ];
    
    $patient_id = createPatient($patient_data);
    
    if ($patient_id && createAppointment($patient_id, $_POST['doctor_id'], $_POST['appointment_date'])) {
        $last_id = $patient_id;
        $success = "Запись успешно создана";
    } else {
        $error = "Ошибка при создании записи";
    }
}

// Получение данных для печати
$print_data = null;
if (isset($_GET['print']) && is_numeric($_GET['print'])) {
    $appointment_id = (int)$_GET['print'];
    $db = DB::getInstance()->getConnection();
    
    $stmt = $db->prepare("SELECT a.id, a.appointment_date, u.full_name as doctor_name, p.encrypted_data 
                          FROM appointments a
                          JOIN users u ON a.doctor_id = u.id
                          JOIN patients p ON a.patient_id = p.id
                          WHERE a.id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $print_data = $result->fetch_assoc();
        $patient_data = json_decode(DB::decrypt($print_data['encrypted_data']), true);
        $print_data['patient'] = $patient_data;
    }
}

$appointments = getAppointmentsForManager();
$doctors = getAllDoctors();
?>

<div class="row no-print">
    <div class="col-12">
        <h2>Кабинет менеджера</h2>
        
        <div class="card mb-4">
            <div class="card-header">
                <h4>Создать новую запись</h4>
            </div>
            <div class="card-body">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= $success ?>

                    </div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ФИО пациента</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Дата рождения</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Телефон</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Адрес</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Врач</label>
                            <select name="doctor_id" class="form-select" required>
                                <?php foreach ($doctors as $doctor): ?>
                                    <option value="<?= $doctor['id'] ?>"><?= htmlspecialchars($doctor['full_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Дата и время приема</label>
                            <input type="datetime-local" name="appointment_date" class="form-control" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Создать запись</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h4>Последние записи</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата приема</th>
                                <th>Врач</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?= $appointment['id'] ?></td>
                                    <td><?= date('d.m.Y H:i', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                                    <td>
                                    <a href="print_ticket.php?id=<?= $appointment['id'] ?>" class="btn btn-sm btn-outline-primary" target="_blank"> Печать </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../footer.php';
?>
