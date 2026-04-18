<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(135deg, #4e73df, #1cc88a); color: white;">

    <div class="container d-flex flex-column justify-content-center align-items-center text-center"
        style="min-height: 100vh;">

        <!-- Заголовок -->
        <h1 class="display-3 fw-bold mb-3">{{ env('APP_NAME') }}</h1>

        <!-- Подзаголовок -->
        <p class="lead mb-4" style="max-width: 500px;">
            Быстрый, безопасный и удобный VPN для всех устройств.
            Подключайся за 1 клик и оставайся анонимным.
        </p>

        <!-- Кнопка -->
        <a href="/cabinet/login" class="btn btn-light btn-lg px-5 shadow">
            <i class="bi bi-box-arrow-in-right"></i> Войти
        </a>

        <!-- Доп инфо -->
        <div class="mt-5">
            <small class="opacity-75">Android • iOS • Windows</small>
        </div>

    </div>

</body>

</html>