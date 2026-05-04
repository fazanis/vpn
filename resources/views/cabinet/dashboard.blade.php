@extends('cabinet.layouts.app')

@section('content')
    @if(!auth()->user()->telegram_id)
        <div class="card mb-4">
            <div class="card-title">
                <span style="color:red">ОБЯЗАТЕЛЬНО после подключения VPN подключитесь в бота Телеграмм для
                получения новостей сервиса</span>
            </div>
            <div class="traffic-chart">
                <a class="btn btn-success" href="{{$tg_bot}}">Перейти к боту</a>
            </div>
        </div>
    @endif


    <div class="card-title">
        Ваши устройства {{$devises->count()}} / 4
        <button class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#exampleModal" {{$devises->count()>=4 ? 'disabled' :''}}>+ добавить устройство
        </button>
    </div>

    <div id="deviceListLeft" style="display:flex;flex-direction:column;gap:8px">
        @foreach($devises as $devise)
            <div class="device-item">
                <div class="device-left">
                    <div>
                        <div class="device-name">{{$devise->name}}</div>
                        <div class="device-meta">{{trafik($devise->trafik) }}</div>
                    </div>
                </div>

                <div class="device-actions">
                    <a href="{{ route('subscription.devises', $devise->ui_id) }}" target="_blank"
                       class="dev-btn success"><i class="bi bi-wifi"></i> Подкл</a>
                    <button class="shareBtn dev-btn primary"
                            data-url="{{ route('subscription.devises', $devise->ui_id) }}"><i class="bi bi-share"></i>
                        Поделиться
                    </button>
                    <form action="{{route('cabinet.devises.destroy',$devise)}}" method="post" id="myForm">
                        @csrf
                        @method('DELETE')
                        <button class="dev-btn danger"
                                onclick="return confirm('Вы уверенны что хотите удалить устройство?')">✕
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{route('cabinet.devises.store')}}" method="post">
                <div class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-title">Добавить устройство</div>
                        <div class="modal-close" data-bs-dismiss="modal">✕</div>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="input-label" style="color: white">Название устройства</label><br>
                            <input name="name" type="text" id="deviceNameInput" class="input"
                                   placeholder="Например: iPhone 15" style="width: 100%">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn-connect" id="submitBtn">Добавить</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('myForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');

            btn.disabled = true;
            btn.innerText = 'Отправка...';
        });

        let isOn = false;
        document.querySelectorAll('.shareBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const url = btn.dataset.url || window.location.href;

                try {
                    if (navigator.share) {
                        await navigator.share({url});
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
        // function toggleVPN() {
        //     const btn = document.getElementById('vpnBtn');
        //     const status = document.getElementById('vpnStatus');
        //
        //     isOn = !isOn;
        //
        //     if (isOn) {
        //         btn.style.background = 'radial-gradient(circle, #22c55e, #16a34a)';
        //         status.innerText = 'Подключено';
        //     } else {
        //         btn.style.background = 'radial-gradient(circle, #00ffd5, #0ea5e9)';
        //         status.innerText = 'Отключено';
        //     }
        // }
    </script>

@endsection
