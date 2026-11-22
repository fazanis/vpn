@extends('cabinet.layouts.app')

@section('content')


    @if(!$user->isAdminReferral() && !$subscription?->isNotTrial())
    <div class="card mb-4">

        <div class="card-title">
            <span>Тарифы</span>
        </div>

        <div class="tarifs-list">
            @foreach($tarifs as $tarif)
                <div class="tarif-item" data-tarif="{{$tarif->id}}">
                    <div class="tarif-info">
                        <div class="tarif-name">
                            {{ $tarif->name }}
                        </div>
                        <div class="tarif-name">
                            @plural($tarif->duration_days, 'день')
                        </div>
                        <div class="tarif-name">
                            @plural($tarif->max_devices, 'устройство')
                        </div>
                    </div>

                    <div class="tarif-price">
                        {{ $tarif->price }} ₽
                    </div>

                    <div class="tarif-action">
                        @if($tarif->is_trial)
                            <a href="{{route('cabinet.subscription.trial.activate',$tarif)}}"
                               class="dev-btn warning">
                                <i class="bi bi-gift"></i>
                                Активировать
                            </a>
                        @else
                            <form action="{{ route('cabinet.subscription.buy', [$tarif,$user]) }}" method="POST">
                                @csrf

                                <button type="submit" class="dev-btn success">
                                    <i class="bi bi-credit-card"></i>
                                    Купить
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    @if($payment && $payment->paid_at==null)
    <div class="card mb-4">
        <div class="tarif-name">
            <span>Неоплеченный счет</span>
        </div>
        <div class="tarifs-list">
            <div class="tarif-item">
                <div class="tarif-info">
                    <div class="tarif-name">
                        {{$payment->tarif->name}} - {{$payment->amount}} р
                    </div>
                </div>
                <div class="tarif-{{$subscription->statusName()['class']}}">
                    <form action="{{ route('cabinet.subscription.pay', [$payment]) }}" target="_blank" method="POST">
                        @csrf

                        <button type="submit" class="dev-btn primary">
                            <i class="bi bi-credit-card"></i>
                            Оплатить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4">

        <div class="tarif-name">
            <span>Текущая подписка</span>
        </div>
        @if($user->isAdminReferral())
            <div class="card-title">
                Безлимитный доступ
            </div>
        @endif
        <div class="tarifs-list">
            @if($subscription)
            <div class="tarif-item" data-sub-id="{{$subscription?->tarif->id}}">
                    <div class="tarif-info">
                        <div class="tarif-name">
                            {{ $subscription?->tarif->name }} - {{ $subscription?->tarif->price }} ₽
                        </div>
                        <div class="tarif-meta">
                            Активна до {{ $subscription?->expires_at?->format('d.m.Y H:i') }}
                        </div>
                        <div class="tarif-meta">
                            Осталось @plural(now()->startOfDay()->diffInDays($subscription?->expires_at?->startOfDay()), 'день')
                        </div>
                    </div>

                    <div class="tarif-{{$subscription->statusName()['class']}}">
                        {{$subscription->statusName()['name']}} <br>
                        @if ($subscription->type!='trial')
                            <form action="{{ route('cabinet.subscription.extend', [$subscription?->tarif,$user]) }}" method="POST">
                                @csrf

                                <button type="submit" class="dev-btn primary">
                                    <i class="bi bi-credit-card"></i>
                                    Продлить
                                </button>
                            </form>
                        @if($subscription->status==\App\Models\Subscription::STATUS_NOT_PAYMENT)
                            <a href="{{ route('cabinet.tarifs') }}" class="dev-btn warning" style="margin-left: 5px">
                                Сменить тариф
                            </a>
                            @endif
                        @endif
                    </div>

                    <div class="tarif-action">
                    </div>
                </div>
            @elseif(!$user->isAdminReferral())
                <div class="tarif-item">
                    <div class="tarif-info">
                        <div class="tarif-name" style="color: red">
                            @if(!$subscription)
                                Активируйте пробный период
                            @else
                                Тариф истек, выберете подходящий
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif
    {{-- Telegram --}}
{{--    @if(!auth()->user()->telegram_id)--}}
{{--        <div class="card mb-4">--}}
{{--            <div class="card-title">--}}
{{--                <span style="color: var(--warn)">--}}
{{--                    Подключите Telegram--}}
{{--                </span>--}}
{{--            </div>--}}
{{--            <div class="telegram-info">--}}
{{--                <div class="telegram-text">--}}
{{--                    После подключения VPN обязательно подключитесь--}}
{{--                    к нашему Telegram-боту, чтобы получать новости--}}
{{--                    и важные уведомления сервиса.--}}
{{--                </div>--}}
{{--                <a class="dev-btn success"--}}
{{--                   href="{{ $tg_bot }}"--}}
{{--                   target="_blank">--}}
{{--                    <i class="bi bi-telegram"></i>--}}
{{--                    Перейти к боту--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}


    {{-- Устройства --}}

    <div class="card {{ $subscription?->status==\App\Models\Subscription::STATUS_ACTIVE || $user->isAdminReferral() ? '' : 'card-disabled' }}">
        <div class="card-title">
            <div>
                Ваши устройства
                <span class="devices-count">
                    {{ $devises->count() }} / {{$user->isAdminReferral() ? '∞' : $subscription?->tarif->max_devices}}
                </span>
            </div>
            <button
                type="button"
                class="dev-btn success"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                {{ !$user->isAdminReferral() && $devises->count() >= $subscription?->tarif->max_devices ? 'disabled' : '' }}>

                <i class="bi bi-plus-lg"></i>
                Добавить устройство

            </button>
        </div>

        <div id="deviceListLeft"
             class="devices-list">
            @forelse($devises as $devise)
                <div class="device-item">
                    <div class="device-left">
                        <div class="device-icon">
                            <i class="bi bi-phone"></i>
                        </div>
                        <div>
                            <div class="device-name">
                                {{ $devise->name }}
                            </div>

                            <div class="device-meta">
                                {{ trafik($devise->trafik) }}
                            </div>
                        </div>
                    </div>
                    <div class="device-actions">
                        <a href="{{ route('subscription.devises', $devise->ui_id) }}"
                           target="_blank"
                           class="dev-btn success">

                            <i class="bi bi-wifi"></i>
                            Подкл

                        </a>
                        <button
                            type="button"
                            class="shareBtn dev-btn primary"
                            data-url="{{ route('subscription.devises', $devise->ui_id) }}">

                            <i class="bi bi-share"></i>
                            Поделиться
                        </button>
                        <form
                            action="{{ route('cabinet.devises.destroy', $devise) }}"
                            method="post"
                            class="delete-device-form">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="dev-btn danger"
                                onclick="return confirm('Вы уверены, что хотите удалить устройство?')">

                                <i class="bi bi-trash"></i>

                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="empty-devices">
                    <i class="bi bi-phone"></i>
                    <div>
                        У вас пока нет устройств
                    </div>
                    <span>
                        Добавьте устройство, чтобы подключить VPN
                    </span>
                </div>
            @endforelse
        </div>
    </div>
    {{-- Добавление устройства --}}
    <div class="modal fade"
         id="exampleModal"
         tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form
                action="{{ route('cabinet.devises.store') }}"
                method="post"
                class="w-100">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            Добавить устройство
                        </div>
                        <button
                            type="button"
                            class="modal-close"
                            data-bs-dismiss="modal">
                            ✕
                        </button>
                    </div>

                    <div class="modal-body">
                        <div>
                            <label class="input-label">
                                Название устройства
                            </label>
                            <input
                                name="name"
                                type="text"
                                class="input"
                                placeholder="Например: iPhone 15"
                                style="width:100%"
                                required>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn-cancel"
                            data-bs-dismiss="modal">
                            Отмена
                        </button>
                        <button
                            type="submit"
                            class="btn-connect"
                            id="submitBtn">

                            Добавить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>

        // Защита от двойной отправки формы
        document.querySelectorAll('.delete-device-form').forEach(form => {
            form.addEventListener('submit', function () {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                }
            });
        });

        // Добавление устройства
        const deviceForm = document.querySelector('#exampleModal form');
        if (deviceForm) {
            deviceForm.addEventListener('submit', function () {
                const btn = document.getElementById('submitBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = 'Создание...';
                }
            });
        }
        // Поделиться
        document.querySelectorAll('.shareBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const url = btn.dataset.url || window.location.href;
                try {
                    if (navigator.share) {
                        await navigator.share({
                            title: 'VPN',
                            url: url
                        });
                    } else {
                        await navigator.clipboard.writeText(url);
                        alert('Ссылка скопирована');
                    }
                } catch (e) {
                    try {
                        await navigator.clipboard.writeText(url);
                        alert('Ссылка скопирована');
                    } catch (e) {
                        prompt('Скопируйте ссылку:', url);
                    }
                }
            });
        });

    </script>

@endsection
