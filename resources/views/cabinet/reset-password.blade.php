<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Пароль" required>
    <input type="password" name="password_confirmation" placeholder="Повторите пароль" required>

    <button type="submit">Сменить пароль</button>
</form>