<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #0b1c2c;
            color: #fff;
        }

        a {
            color: #2bc8ff;
            text-decoration: none;
        }

        a:hover {
            color: #1da7cc;
        }

        .navbar,
        .card {
            background: #0e2a40;
            border: none;
            color: white;
        }

        .btn-primary {
            background-color: #2bc8ff;
            border-color: #2bc8ff;
        }

        .btn-primary:hover {
            background-color: #1da7cc;
            border-color: #1da7cc;
        }

        .card {
            border-radius: 10px;
        }

        .badge-warning {
            background-color: #ffc107;
        }

        .badge-info {
            background-color: #2bc8ff;
        }

        /* Анимация карточки */
        .card {
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(43, 200, 255, 0.2);
        }

        /* Анимация блока с ссылкой */
        .input-group {
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
        }

        .input-group:hover {
            box-shadow: 0 0 10px rgba(43, 200, 255, 0.3);
        }

        /* Поле ссылки */
        #refLink {
            background: #0b1c2c;
            color: #2bc8ff;
            border: 1px solid #2bc8ff33;
            transition: all 0.3s ease;
        }

        #refLink:focus {
            box-shadow: none;
            border-color: #2bc8ff;
        }

        /* Кнопки */
        .btn {
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    @include('cabinet.partials.header')

    <main class="container my-4">
        @yield('content')
    </main>

    @include('cabinet.partials.footer')

</body>

</html>
