<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}- Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <style>
        body {
            background: #0b1c2c;
            color: #fff;
        }

        a {
            text-decoration: none;

        }

        .btn-primary {
            background-color: #2bc8ff;
            border-color: #2bc8ff;
        }

        .btn-primary:hover {
            background-color: #1da7cc;
            border-color: #1da7cc;
        }

        .form-control:focus {
            background: #0e2a40;
            color: #fff;
            border-color: #2bc8ff;
            box-shadow: none;
        }

        /*  .form-control {
            background: #0e2a40;
            color: #fff;
            border: 1px solid #1e3a50;
        }

         */
    </style> --}}
</head>

{{--

<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="w-100"
        style="max-width: 400px;border:1px solid #0e2a40; border-radius: 5%; padding: 15px; background-color: #1e3a50;">
        <h3 class="text-center mb-4">{{ env('APP_NAME') }}</h3>
        <div class="mb-3">
            <a href="{{ route('google.redirect', 'google') }}" class="form-control text-center">
                🔵 Зайти с Google
            </a>
        </div>

        <p class="text-center">
            или
        </p>

        <form method="POST" action="">
            @csrf
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="you@example.com" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Пароль" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Вход</button>
            <a href="" class="btn btn-primary w-100 mt-5">Регистрация</a>
            <div class="text-center mt-3">
                <a href="" class="text-info">Забыли пароль?</a>
            </div>
        </form>
    </div>
</body> --}}
<style>
    input {
        background: #dadcdd !important;
        border: 1px solid #2c4d66 !important;
        color: black !important;
    }

    input::placeholder {
        color: #9bb3c7;
    }

    .nav-pills .nav-link {
        color: #9bb3c7;
        background-color: transparent;
        border-radius: 10px;
        transition: 0.2s;
    }

    .nav-pills .nav-link:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.1);
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
        font-weight: 600;
    }
</style>

<body class="d-flex align-items-center justify-content-center vh-100 bg-dark">

    <div class="w-100" style="max-width: 420px;">

        <div class="card shadow-lg border-0" style="background:#1e3a50; border-radius:16px;">
            <div class="card-body p-4 text-white">

                <h3 class="text-center mb-4">{{ env('APP_NAME') }}</h3>
                @include('cabinet.partials.errors')
                @if(session('resend'))
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <input name="email" value="{{ session('email') }}">
                        <button class="btn btn-warning mt-2 mb-2">
                            Отправить письмо повторно
                        </button>
                    </form>
                @else
                    <!-- Tabs -->
                    <ul class="nav nav-pills nav-justified mb-4" id="authTab">
                        <li class="nav-item">
                            <button class="nav-link active w-100" data-bs-toggle="pill" data-bs-target="#login">
                                Вход
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100  btn-close-white" data-bs-toggle="pill"
                                data-bs-target="#register">
                                Регистрация
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- LOGIN -->
                        <div class="tab-pane fade show active" id="login">

                            <a href="{{ route('google.redirect', 'google') }}" class="btn btn-light w-100 mb-3">
                                🔵 Войти через Google
                            </a>
                            <p class="text-center">
                                или
                            </p>
                            <form method="POST" action="{{ route('login.singin') }}">
                                @csrf

                                <input type="email" value="{{ old('email') }}" name="email" class="form-control mb-2"
                                    placeholder="Email" required>

                                <input type="password" name="password" class="form-control mb-3" placeholder="Пароль"
                                    required>

                                <button class="btn btn-primary w-100">Войти</button>
                            </form>

                            <div class="text-center mt-3">
                                <a href="{{ route('forgot-password') }}" class="text-info">Забыли пароль?</a>
                            </div>

                        </div>

                        <!-- REGISTER -->
                        <div class="tab-pane fade" id="register">

                            <form method="POST" action="{{ route('register') }}" autocomplete="off">
                                @csrf

                                <input type="text" name="name" class="form-control mb-2" placeholder="Имя" required
                                    autocomplete="off">

                                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required
                                    autocomplete="off">

                                <input type="password" name="password" class="form-control mb-2" placeholder="Пароль"
                                    required autocomplete="off">

                                <input type="password" name="password_confirmation" class="form-control mb-3"
                                    placeholder="Повтор пароля" required autocomplete="off">

                                <button class="btn btn-success w-100">Создать аккаунт</button>
                            </form>

                        </div>

                    </div>
                @endif
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>