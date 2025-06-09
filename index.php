<?php
session_start();
require_once 'header.php';
require_once 'auth.php';

checkAuth();
?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h3>Главная страница</h3>
            </div>
            <div class="card-body">
                <?php if (getUserRole() === 'manager'): ?>
                    <p>Вы авторизованы как менеджер. Вы можете записывать пациентов на прием к врачам.</p>
                    <a href="/clinic/manager/" class="btn btn-primary">Перейти в кабинет менеджера</a>
                <?php elseif (getUserRole() === 'doctor'): ?>
                    <p>Вы авторизованы как врач. Вы можете просматривать записи пациентов и ставить диагнозы.</p>
                    <a href="/clinic/doctor/" class="btn btn-primary">Перейти в кабинет врача</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'footer.php';
?>
