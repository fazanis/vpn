@extends('cabinet.layouts.app')

@section('content')
    <div class="mb-4">
        <h3><a href="{{ route('cabinet.dashboard') }}" class="btn btn-warning">
                <span class="bi bi-chevron-left"></span>
            </a>
            Подписка</h3>
    </div>

    <div class="row g-3">
        @foreach ($tarifs as $tarif)
            <div class="col-md-3">
                <a href="{{ route('cabinet.subscription.index') }}">
                    <div class="card p-3">
                        <h5>{{ $tarif->name }}
                        </h5>

                        <p><i class="bi bi-phone-fill"></i> Устройств {{ $tarif->max_devices }}</p>
                        <p><i class="bi bi-calendar-check-fill"></i> дней
                            {{ $tarif->duration_days }}</p>
                    </div>
                </a>
            </div>
        @endforeach

        {{-- <div class="col-md-6">
            <div class="card p-3">
                <h5>Баланс</h5>
                <p class="fs-3">0.00 ₽</p>
                <button class="btn btn-success">Пополнить баланс</button>
            </div>
        </div> --}}
        {{-- <div class="col-md-12">
            <a href="">
                <div class="card p-3">
                    <h5><i class="bi bi-people"></i> Рефералы</h5>

                    <p class="fs-3 mb-1">{{ $refers->count() }}</p>
                    <p>Поделитесь ссылкой и получите 7 дней бесплатно</p>

                    <div class="input-group mt-2">
                        <input type="text" id="refLink" class="form-control"
                            value="{{ config('app.url') . '/cabinet/login?ref=' . auth()->user()->referral_code }}"
                            readonly>

                        <button class="btn btn-success" onclick="copyRef()">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                    <small id="copyMessage" class="text-success mt-2 d-none">
                        Скопировано!
                    </small>
                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-primary w-100" onclick="shareRef()">
                            <i class="bi bi-share"></i> Поделиться
                        </button>
                    </div>
                </div>
            </a>
        </div> --}}
        {{--
    </div> --}}

    {{-- <div class="card p-3 mt-3">
        <h5>Подписка</h5>
        <p>Ваша подписка истекла</p>
        <a href="{{ route('cabinet.subscription.index') }}" class="btn btn-success">Купить подписку</a>
    </div>
    <div class="card p-3 mt-3">
        <h5>Новости и обновления</h5>
        <p>Теперь можно подарить свободу! Купите подписку ZeroPing VPN в подарок...</p>
    </div>

    <script>
        function copyRef() {
            const input = document.getElementById('refLink');

            navigator.clipboard.writeText(input.value).then(() => {
                const msg = document.getElementById('copyMessage');
                msg.classList.remove('d-none');

                setTimeout(() => {
                    msg.classList.add('d-none');
                }, 2000);
            });
        }
        function shareRef() {
            const url = document.getElementById('refLink').value;

            if (navigator.share) {
                navigator.share({
                    title: 'ZeroPing VPN',
                    text: 'Получите 7 дней бесплатно по моей ссылке!',
                    url: url
                });
            } else {
                // fallback — если браузер не поддерживает
                copyRef();
                alert('Ссылка скопирована! Вставьте её куда нужно.');
            }
        }
    </script> --}}
@endsection