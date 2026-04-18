@extends('cabinet.layouts.app')

@section('content')
<style>
    body {
        background: radial-gradient(circle at top, #0b2f2f, #020d0d);
        color: #d7fefe;
        font-family: 'Segoe UI', sans-serif;
        padding-bottom: 70px;
    }

    .card-custom {
        background: rgba(10, 30, 30, 0.6);
        border: 1px solid rgba(0, 255, 200, 0.1);
        border-radius: 16px;
        backdrop-filter: blur(10px);
    }

    .btn-neon {
        background: linear-gradient(90deg, #00d2b5, #0ea5e9);
        border: none;
        border-radius: 12px;
        color: white;
    }

    .badge-custom {
        background: rgba(0, 255, 200, 0.15);
        color: #00ffd5;
    }

    .text-muted-custom {
        color: #7dd3c7;
    }

    .balance {
        font-size: 26px;
        font-weight: bold;
    }

    .vpn-toggle {
        height: 120px;
        width: 120px;
        border-radius: 50%;
        background: radial-gradient(circle, #00ffd5, #0ea5e9);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #000;
        margin: 20px auto;
        box-shadow: 0 0 30px rgba(0, 255, 200, 0.4);
        cursor: pointer;
    }

    .status {
        text-align: center;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }

        h5 {
            font-size: 18px;
        }

        .balance {
            font-size: 22px;
        }
    }
</style>


<div class="container py-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h5 class="mb-0">Привет, {{ auth()->user()->name }}</h5>
        <div>
            <i class="bi bi-bell me-3"></i>
            <i class="bi bi-person-circle"></i>
        </div>
    </div>

    <!-- Devices -->
    <div class="card-custom p-3 mb-3">
        <div class="mb-2"><strong>Устройства {{ $devises->count() }}/4</strong></div>
        @foreach($devises as $devise)
        <div class="d-flex flex-column gap-2 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="bi bi-phone"></i> {{ $devise->name }}

                </div>

            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('subscription.devises', $devise->ui_id) }}" target="_blank"
                    class="btn btn-success btn-sm d-flex align-items-center gap-1 px-2 py-1">
                    <i class="bi bi-wifi"></i>
                    <small>Подкл.</small>
                </a>
                <button data-url="{{ route('subscription.devises', $devise->ui_id) }}"
                    class="shareBtn btn btn-primary btn-sm d-flex align-items-center gap-1 px-2 py-1">
                    <i class="bi bi-share"></i>
                    <small>Поделиться</small>
                </button>
                <form action="{{ route('cabinet.devises.destroy', $devise) }}" method="POST"
                      onsubmit="return confirm('Вы уверенны что хотите удалить устройство?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center gap-1 px-2 py-1">
                        <i class="bi bi-trash"></i>
                        <small>Удалить</small>
                    </button>
                </form>
            </div>

            @endforeach
            {{-- <div class="d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-laptop"></i> MacBook
                        <div class="text-muted-custom small">Последний вход: вчера</div>
                    </div>
                    <span class="badge bg-secondary">Оффлайн</span>
                </div>
                <div class="d-flex gap-2">
                    <button
                        class="btn btn-success flex-fill d-flex flex-column align-items-center justify-content-center py-2">
                        <i class="bi bi-wifi fs-5"></i>
                        <small>Подкл.</small>
                    </button>
                    <button
                        class="btn btn-primary flex-fill d-flex flex-column align-items-center justify-content-center py-2">
                        <i class="bi bi-share fs-5"></i>
                        <small>Поделиться</small>
                    </button>
                    <button
                        class="btn btn-danger flex-fill d-flex flex-column align-items-center justify-content-center py-2">
                        <i class="bi bi-trash fs-5"></i>
                        <small>Удалить</small>
                    </button>
                </div>
            </div> --}}

            <button class="btn btn-outline-light w-100 mt-2" data-bs-toggle="modal" data-bs-target="#exampleModal" {{ $devises->count() >= 4 ? 'disabled' : '' }}>+ Добавить устройство</button>
        </div>
        <!-- Subscription -->
        {{-- <div class="card-custom p-3 my-3">
            <div class="d-flex flex-column flex-md-row justify-content-between">
                <div>
                    <strong>Подписка</strong>
                    <span class="badge bg-warning text-dark ms-2">Тестовая</span>
                    <div class="text-muted-custom small mt-1">
                        До 31.03.2026
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-neon w-100 py-3 mb-4">+ Купить тариф</button>

        <!-- Stats -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6">
                <div class="card-custom p-3">
                    <div class="text-muted-custom mb-2">Баланс</div>
                    <div class="balance">0 ₽</div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card-custom p-3">
                    <div class="text-muted-custom mb-2">Рефералы</div>
                    <div class="balance">0</div>
                </div>
            </div>
        </div>

        <!-- Promo -->
        <div class="card-custom p-3 mb-3 d-flex justify-content-between align-items-center">
            <div>
                <strong>Испытай удачу</strong>
                <div class="text-muted-custom small">Крути колесо</div>
            </div>
            <i class="bi bi-chevron-right"></i>
        </div> --}}



    </div>

    <!-- Bottom Nav -->
    <nav class="d-md-none fixed-bottom bg-dark d-flex justify-content-around py-2">
        <i class="bi bi-house"></i>
        <i class="bi bi-lightning"></i>
        <i class="bi bi-globe"></i>
        <i class="bi bi-person"></i>
    </nav>

</div>
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('cabinet.devises.store') }}" method="post">
            @csrf
            <div class="modal-content" style="background-color: #0e2a40; color: #fff;">

                <div class="modal-header" style="border-bottom: 1px solid #1f4a6b;">
                    <h5 class="modal-title">Добавление устройства</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input name="name" class="form-control"
                               style="background-color: #163a57; color: #fff; border: 1px solid #1f4a6b;"
                               placeholder="Например: мой андроид">
                    </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid #1f4a6b;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Закрыть
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Сохранить
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    let isOn = false;
    document.querySelectorAll('.shareBtn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const url = btn.dataset.url || window.location.href;

            try {
                if (navigator.share) {
                    await navigator.share({ url });
                } else {
                    await navigator.clipboard.writeText(url);
                    alert('Ссылка скопирована');
                }
            } catch (e) {
                await navigator.clipboard.writeText(url);
                alert('Ссылка скопирована');
            }
        });
    });
    function toggleVPN() {
        const btn = document.getElementById('vpnBtn');
        const status = document.getElementById('vpnStatus');

        isOn = !isOn;

        if (isOn) {
            btn.style.background = 'radial-gradient(circle, #22c55e, #16a34a)';
            status.innerText = 'Подключено';
        } else {
            btn.style.background = 'radial-gradient(circle, #00ffd5, #0ea5e9)';
            status.innerText = 'Отключено';
        }
    }
</script>


@endsection
