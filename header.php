<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клиника "Здоровье"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="">Клиника "Здоровье"</a>
            
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?><a href="logout.php" class="btn btn-outline-light">Выйти</a><?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
