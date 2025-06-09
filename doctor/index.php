<?php
session_start();
require_once '../header.php';
require_once '../auth.php';
require_once '../functions.php';

checkAuth();

if (getUserRole() !== 'doctor') {
    header('Location: /clinic/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    if (updateDiagnosis($_POST['appointment_id'], $_POST['diagnosis'], $_POST['recommendations'])) {
        $success = "Диагноз и рекомендации успешно сохранены";
    } else {
        $error = "Ошибка при сохранении данных";
    }
}

$appointments = getAppointmentsForDoctor($_SESSION['user_id']);
?>

<div class="row">
    <div class="col-12">
        <h2>Кабинет врача</h2>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h4>Записи пациентов</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Дата приема</th>
                                <th>Диагноз</th>
                                <th>Рекомендации</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td><?= $appointment['id'] ?></td>
                                    <td><?= date('d.m.Y H:i', strtotime($appointment['appointment_date'])) ?></td>
                                    <td><?= $appointment['diagnosis'] ? htmlspecialchars($appointment['diagnosis']) : '—' ?></td>
                                    <td><?= $appointment['recommendations'] ? htmlspecialchars($appointment['recommendations']) : '—' ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                data-bs-target="#editModal<?= $appointment['id'] ?>">
                                            Редактировать
                                        </button>
                                    </td>
                                </tr>
                                
                                <!-- Модальное окно редактирования -->
                                <div class="modal fade" id="editModal<?= $appointment['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Редактирование записи #<?= $appointment['id'] ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Диагноз</label>
                                                        <textarea name="diagnosis" class="form-control" rows="3"><?= 
                                                            $appointment['diagnosis'] ?? '' 
                                                        ?></textarea>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Рекомендации</label>
                                                        <textarea name="recommendations" class="form-control" rows="3"><?= 
                                                            $appointment['recommendations'] ?? '' 
                                                        ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
