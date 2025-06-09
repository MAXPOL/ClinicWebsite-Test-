<?php
session_start();
require_once 'header.php';
require_once 'auth.php';

if (isset($_SESSION['user_id'])) {
    header('Location: /clinic/');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['access_code'])) {
    if (login($_POST['access_code'])) {
        header('Location: /clinic/');
        exit();
    } else {
        $error = "Неверный код доступа";
    }
}
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h3>Авторизация</h3>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form id="loginForm" method="POST">
                    <div class="mb-3">
                        <label for="access_code" class="form-label">Код доступа (10 цифр)</label>
                        <input type="password" class="form-control" id="access_code" name="access_code" 
                               maxlength="10" pattern="\d{10}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('access_code').addEventListener('input', function(e) {
    if (this.value.length === 10) {
        document.getElementById('loginForm').submit();
    }
});
</script>

<?php
require_once 'footer.php';
?>
