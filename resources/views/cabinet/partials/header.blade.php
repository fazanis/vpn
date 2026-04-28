<nav class="navbar navbar-expand-lg navbar-dark px-3">
    <a class="navbar-brand" href="#">{{ env('APP_NAME') }}</a>

    <!-- Кнопка-бургер для мобильных -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
        aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Меню -->
    <div class="collapse navbar-collapse" id="navbarMenu">
        {{-- <ul class="navbar-nav ms-auto m-auto"> --}}
            <ul class="navbar-nav">
                {{-- <li class="nav-item"><a class="nav-link" href="#">Баланс</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Рефералы</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Новости</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Профиль</a></li> --}}
{{--                <li class="nav-item">--}}
{{--                    <div class="card-login">--}}
{{--                        <form method="POST" action="{{ route('cabinet.logout') }}">--}}
{{--                            @csrf--}}
{{--                            <button type="submit" class="btn btn-success float-right">Выход</button>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </li>--}}
            </ul>
    </div>
</nav>
