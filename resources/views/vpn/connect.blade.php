@extends('cabinet.layouts.app')
@section('content')
    <div class="mb-4 col-md-8 mx-auto">
        <h3>Подключение устройства</h3>
    </div>

    <div class="row g-3">

        <div class="col-md-8 mx-auto">
            <ul class="nav nav-pills nav-justified" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#happ">Happ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#v2nray">v2nray</a>
                </li>
            </ul>
            <div class="tab-content mt-3">

                <div class="tab-pane fade show active" id="happ">
                    @include('cabinet.partials.happ')
                </div>

                <div class="tab-pane fade" id="v2nray">
                    @include('cabinet.partials.v2nray')
                </div>
                @if(!$devise->user->telegram_id)
                    <div class="col-md-12 mx-auto mb-3">
                        <div class="card p-3">
                            <h2>4. Подключение к боту в Телеграм</h2>
                            <p>Подключение нужно для получения новостей и изменений в сервисе</p>
                            <p>Подключайтесь полсе продключения ВПН для коректной работы</p>
                            <a href="{{ $tg_bot }}" class="btn btn-success">Подключить</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
    <script>
        const links = {
            Android: 'https://play.google.com/store/apps/details?id=com.happproxy&hl=ru',
            iOS: 'https://apps.apple.com/ru/app/happ-proxy-utility-plus/id6746188973',
            Windows: 'https://github.com/Happ-proxy/happ-desktop/releases/latest/download/setup-Happ.x64.exe'
        };

        const select = document.getElementById('platformSelectHapp');
        const link = document.getElementById('downloadLink');

        function updateLink() {
            const platform = select.value;
            link.href = links[platform];
            link.textContent = 'Скачать для ' + platform;
        }

        // при загрузке страницы
        updateLink();

        // при смене
        select.addEventListener('change', updateLink);

        const linksv2 = {
            Android: 'https://github.com/2dust/v2rayNG/releases/download/2.0.18/v2rayNG_2.0.18-fdroid_universal.apk',
            Windows: 'https://github.com/2dust/v2rayN/releases/download/5.29/v2rayN-Core.zip'
        };

        const selectv2 = document.getElementById('platformSelectV2ray');
        const linkv2 = document.getElementById('v2downloadLink');

        function updateLinkv2() {
            const platformv2 = selectv2.value;
            linkv2.href = linksv2[platformv2];
            linkv2.textContent = 'Скачать для ' + platformv2;
        }

        // при загрузке страницы
        updateLinkv2();

        // при смене
        selectv2.addEventListener('change', updateLinkv2);
    </script>

@endsection