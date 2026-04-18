<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}- Вход</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


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
                <a href="{{ route('login') }}" class="btn btn-danger mt-2 mb-2">
                    Назад
                </a>
                <form method="POST" action="{{ route('forgot-password') }}">
                    @csrf
                    <input type="email" value="{{ old('email') }}" name="email" class="form-control mb-2"
                        placeholder="Email" required>
                    <button class="btn btn-warning mt-2">
                        Отправить письмо с сылкой для востановления
                    </button>
                </form>

            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>