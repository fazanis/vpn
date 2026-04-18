@extends('cabinet.layouts.app')

@section('content')
    <style>
        .form-control::placeholder {
            color: white !important;
        }

        .device-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .device-info {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* кнопки */
        .device-actions {
            display: flex;
            gap: 6px;
        }

        /* убираем кривой inline-flex */
        .device-actions form {
            margin: 0;
        }

        /* 📱 мобильная версия */
        @media (max-width: 576px) {
            .device-row {
                flex-direction: column;
                align-items: stretch;
            }

            .device-actions {
                flex-wrap: wrap;
                width: 100%;
            }

            .device-actions .btn {
                flex: 1 1 100%;
            }

            .device-actions form {
                width: 100%;
            }

            .device-actions form button {
                width: 100%;
            }
        }
    </style>
    <div class="mb-4">
        <h3>Добро пожаловать, {{ auth()->user()->name }}!</h3>
        {{-- <span class="badge badge-info">Джуниор</span> --}}
    </div>

    <div class="row g-3">
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Ваши устройства {{ $devises->count() }}/4</h5>
                {{-- <a href="" class="btn btn-success">Добавить устройство</a> --}}
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" {{ $devises->count() >= 4 ? 'disabled' : '' }}>
                    Добавить устройство
                </button>
                <br>
                <p>Для подключения устройства нажмите на кнопку "Подключить"</p>
                <br>
                @forelse ($devises as $devise)
                    <div class="device-row">
                        <div class="device-info">
                            <i class="bi bi-phone-fill"></i>
                            <span>{{ $devise->name }}</span>
                        </div>

                        <div class="device-actions">
                            <a href="{{ route('subscription.devises', $devise->ui_id) }}" class="btn btn-success"
                                target="_blank">Подключить</a>

                            <button class="shareBtn btn btn-primary"
                                data-url="{{ route('subscription.devises', $devise->ui_id) }}">
                                Поделиться
                            </button>

                            <form action="{{ route('cabinet.devises.destroy', $devise) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить устройство?')">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Удалить
                                </button>
                            </form>
                        </div>
                    </div>
                @empty <p>У вас пока нет устройств</p>
                @endforelse
            </div>
        </div>
        <!-- Button trigger modal -->


        <!-- Modal -->
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
            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.shareBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const url = btn.dataset.url || window.location.href;

                if (navigator.share) {
                    await navigator.share({ url });
                } else {
                    await navigator.clipboard.writeText(url);
                    alert('Ссылка скопирована');
                }
            });
        });

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
    </script>
@endsection