<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Dashboard</title>
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/cabinet.css') }}">
</head>

<body>
<div class="shell">
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <svg viewBox="0 0 18 18" fill="none">
                    <path d="M9 1L16 5V13L9 17L2 13V5L9 1Z" stroke="#000" stroke-width="1.5" fill="none" />
                    <path d="M9 5L13 7.5V12.5L9 15L5 12.5V7.5L9 5Z" fill="#000" />
                </svg>
            </div>
            <div>
                <span class="logo-name">{{env('APP_NAME')}}</span>
                <span class="logo-badge">PRO</span>
            </div>
        </div>

        <div class="nav-section">

            <div class="nav-label">Главное</div>
            <div class="nav-item active">
                <svg viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.3" />
                    <path d="M8 2C8 2 6 5 6 8s2 6 2 6M8 2c0 0 2 3 2 6s-2 6-2 6M2 8h12" stroke="currentColor" stroke-width="1.3" />
                </svg>
                Обзор
            </div>
{{--            <div class="nav-item">--}}
{{--                <svg viewBox="0 0 16 16" fill="none">--}}
{{--                    <rect x="2" y="2" width="12" height="10" rx="2" stroke="currentColor" stroke-width="1.3" />--}}
{{--                    <path d="M5 14h6M8 12v2" stroke="currentColor" stroke-width="1.3" />--}}
{{--                </svg>--}}
{{--                Серверы--}}
{{--            </div>--}}
{{--            <div class="nav-item">--}}
{{--                <svg viewBox="0 0 16 16" fill="none">--}}
{{--                    <path d="M8 1l2.5 4.5L16 6.5l-4 4 1 5.5L8 13.5l-5 2.5 1-5.5-4-4 5.5-1z" stroke="currentColor"--}}
{{--                          stroke-width="1.3" />--}}
{{--                </svg>--}}
{{--                Статистика--}}
{{--            </div>--}}
        </div>

        <div class="nav-section">
            <div class="nav-label">Аккаунт</div>
{{--            <div class="nav-item">--}}
{{--                <svg viewBox="0 0 16 16" fill="none">--}}
{{--                    <circle cx="8" cy="5.5" r="2.5" stroke="currentColor" stroke-width="1.3" />--}}
{{--                    <path d="M2 13.5c0-3 2.7-5 6-5s6 2 6 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />--}}
{{--                </svg>--}}
{{--                Профиль--}}
{{--            </div>--}}
{{--            <div class="nav-item">--}}
{{--                <svg viewBox="0 0 16 16" fill="none">--}}
{{--                    <rect x="2" y="2" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.3" />--}}
{{--                    <path d="M5 8h6M8 5v6" stroke="currentColor" stroke-width="1.3" />--}}
{{--                </svg>--}}
{{--                Подписка--}}
{{--            </div>--}}
            <div class="nav-item" onclick="this.querySelector('form').submit()" style="color: var(--danger);">
                <svg viewBox="0 0 16 16" fill="none">
                    <path d="M6 2H3a1 1 0 00-1 1v10a1 1 0 001 1h3" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M10 12l3-4-3-4M13 8H6" stroke="currentColor" stroke-width="1.3"/>
                </svg>
                Выход

                <form action="{{ route('cabinet.logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
{{--            <div class="nav-item">--}}
{{--                <form action="{{route('cabinet.logout')}}" method="post">--}}
{{--                    @csrf--}}
{{--                    <button type="submit" class="btn btn-app" style="color: red"><i class="bi bi-box-arrow-left"></i>--}}

{{--                        Выход</button>--}}
{{--                </form>--}}

{{--            </div>--}}
        </div>

        <div class="sidebar-bottom">
            <div class="user-row">
                <div class="avatar">АИ</div>
                <div class="user-info">
                    <div class="user-name">{{auth()->user()->name}}</div>
                    <div class="user-plan">PRO </div>
{{--                    · до мар 2026--}}
                </div>
            </div>
        </div>
    </aside>

    <main class="main">
        <div class="top-bar">
            <div>
                <div class="page-title">Личный кабинет</div>
                <div class="page-sub">Добро пожаловать</div>
            </div>
        </div>
        @include('cabinet.partials.errors')

        <div class="content-grid">
            <div>
                {{--            <div class="connect-hero">--}}

                {{--                <div class="conn-info">--}}
                {{--                    <ul>--}}
                {{--                        <li>Скачать Happ <button class="btn btn-success">Скачать</button></li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                <div class="card mb-3">
        @yield('content')
{{--                    <div class="card">--}}
{{--                        <div class="card-title">--}}
{{--                            Трафик (24ч)--}}
{{--                            <span>МБ/с</span>--}}
{{--                        </div>--}}
{{--                        <div class="traffic-chart">--}}
{{--                            <svg viewBox="0 0 560 80" preserveAspectRatio="none">--}}
{{--                                <defs>--}}
{{--                                    <linearGradient id="g1" x1="0" y1="0" x2="0" y2="1">--}}
{{--                                        <stop offset="0%" stop-color="rgba(0,229,160,0.3)" />--}}
{{--                                        <stop offset="100%" stop-color="rgba(0,229,160,0)" />--}}
{{--                                    </linearGradient>--}}
{{--                                    <linearGradient id="g2" x1="0" y1="0" x2="0" y2="1">--}}
{{--                                        <stop offset="0%" stop-color="rgba(0,149,255,0.25)" />--}}
{{--                                        <stop offset="100%" stop-color="rgba(0,149,255,0)" />--}}
{{--                                    </linearGradient>--}}
{{--                                </defs>--}}
{{--                                <path--}}
{{--                                    d="M0 65 C40 60 60 20 100 30 C130 38 150 55 190 40 C230 25 250 50 290 35 C320 22 340 45 380 38 C420 30 440 55 480 42 C510 32 530 20 560 28 L560 80 L0 80Z"--}}
{{--                                    fill="url(#g1)" />--}}
{{--                                <path--}}
{{--                                    d="M0 65 C40 60 60 20 100 30 C130 38 150 55 190 40 C230 25 250 50 290 35 C320 22 340 45 380 38 C420 30 440 55 480 42 C510 32 530 20 560 28"--}}
{{--                                    stroke="var(--accent)" stroke-width="1.5" fill="none" />--}}
{{--                                <path--}}
{{--                                    d="M0 72 C50 68 70 45 110 52 C150 58 170 42 210 50 C240 56 260 38 300 48 C340 56 360 36 400 45 C440 52 460 40 500 48 C530 54 545 42 560 46 L560 80 L0 80Z"--}}
{{--                                    fill="url(#g2)" />--}}
{{--                                <path--}}
{{--                                    d="M0 72 C50 68 70 45 110 52 C150 58 170 42 210 50 C240 56 260 38 300 48 C340 56 360 36 400 45 C440 52 460 40 500 48 C530 54 545 42 560 46"--}}
{{--                                    stroke="var(--accent2)" stroke-width="1.5" fill="none" />--}}
{{--                            </svg>--}}
{{--                        </div>--}}
{{--                        <div--}}
{{--                            style="display:flex;justify-content:space-between;margin-top:10px;font-size:11px;font-family:'DM Mono';color:var(--muted)">--}}
{{--                            <span>00:00</span><span>06:00</span><span>12:00</span><span>18:00</span><span>Сейчас</span>--}}
{{--                        </div>--}}
{{--                        <div style="display:flex;gap:18px;margin-top:14px">--}}
{{--                            <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-family:'DM Mono'">--}}
{{--                                <div style="width:10px;height:2px;background:var(--accent);border-radius:1px"></div>--}}
{{--                                Загрузка--}}
{{--                            </div>--}}
{{--                            <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-family:'DM Mono'">--}}
{{--                                <div style="width:10px;height:2px;background:var(--accent2);border-radius:1px"></div>--}}
{{--                                Отдача--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            <div style="display:flex;flex-direction:column;gap:18px">
                <div class="card">
                    <div class="card-title">
                        Серверы
                        <!-- <span style="cursor:pointer;color:var(--accent)">все →</span> -->
                    </div>
                    @isset($servers)
                    <div class="server-list">
                        @foreach($servers as $server)
                        <div class="server-item active" onclick="selectServer(this, 'Германия — Франкфурт')">
                            <div class="flag">{{$server->server->flag}}</div>
                            <div style="flex:1">
                                <div class="srv-name">{{$server->server->name}}</div>
                                <div class="srv-city">{{$server->protocol}} / {{$server->type}} / {{$server->security}} </div>
                            </div>
                            <div class="signal-bars">
                                <div class="bar b1 lit"></div>
                                <div class="bar b2 lit"></div>
                                <div class="bar b3 lit"></div>
                                <div class="bar b4 lit"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endisset
                </div>

{{--                <div class="card">--}}
{{--                    <div class="card-title">Использование данных</div>--}}
{{--                    <div class="usage-section">--}}
{{--                        <div class="usage-label">--}}
{{--                            <span>Месячный лимит</span>--}}
{{--                            <span class="ub">47.3 / 100 ГБ</span>--}}
{{--                        </div>--}}
{{--                        <div class="usage-bar">--}}
{{--                            <div class="usage-fill" style="width:47%"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="usage-section">--}}
{{--                        <div class="usage-label">--}}
{{--                            <span>Загружено сегодня</span>--}}
{{--                            <span class="ub">2.1 ГБ</span>--}}
{{--                        </div>--}}
{{--                        <div class="usage-bar">--}}
{{--                            <div class="usage-fill" style="width:21%"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="speed-row">--}}
{{--                        <div class="speed-card">--}}
{{--                            <div>--}}
{{--                                <div class="speed-dir">↓ ВНИЗ</div>--}}
{{--                                <div class="speed-num">187</div>--}}
{{--                                <div class="speed-unit">Мбит/с</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="speed-card">--}}
{{--                            <div>--}}
{{--                                <div class="speed-dir">↑ ВВЕРХ</div>--}}
{{--                                <div class="speed-num">94</div>--}}
{{--                                <div class="speed-unit">Мбит/с</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- <div class="card">
                  <div class="card-title">Безопасность</div>
                  <div class="kill-row">
                    <div>
                      <div class="kill-label">Kill Switch</div>
                      <div class="kill-desc">Блокировка при разрыве</div>
                    </div>
                    <div class="toggle on" onclick="this.classList.toggle('on')"></div>
                  </div>
                  <div class="kill-row">
                    <div>
                      <div class="kill-label">DNS-защита</div>
                      <div class="kill-desc">Скрыть DNS-запросы</div>
                    </div>
                    <div class="toggle on" onclick="this.classList.toggle('on')"></div>
                  </div>
                  <div class="kill-row">
                    <div>
                      <div class="kill-label">Автоподключение</div>
                      <div class="kill-desc">При запуске системы</div>
                    </div>
                    <div class="toggle" onclick="this.classList.toggle('on')"></div>
                  </div>
                  <div class="proto-row">
                    <div class="proto-chip active" onclick="selectProto(this)">WireGuard</div>
                    <div class="proto-chip" onclick="selectProto(this)">OpenVPN</div>
                    <div class="proto-chip" onclick="selectProto(this)">IKEv2</div>
                  </div>
                </div> -->
            </div>
        </div>
    </main>
</div>

<script>
    function openDeviceModal() {
        document.getElementById('deviceModal').classList.add('active');
    }

    function closeDeviceModal() {
        document.getElementById('deviceModal').classList.remove('active');
    }

    function submitDevice() {
        const name = document.getElementById('deviceNameInput').value;
        const type = document.getElementById('deviceType').value;

        if (!name) {
            alert('Введите название устройства');
            return;
        }

        const list = document.getElementById('deviceListLeft');
        const empty = document.getElementById('emptyDevices');

        if (empty) empty.style.display = 'none';
        if (list) list.style.display = 'flex';

        const el = document.createElement('div');
        el.className = 'device-item';

        el.innerHTML = `
    <div class="device-left">
      <div>
        <div class="device-name">${name}</div>
        <div class="device-meta">${type.toUpperCase()}</div>
      </div>
    </div>

    <div class="device-actions">
      <button class="dev-btn" onclick="connectDevice('${name}')">Подкл</button>
      <button class="dev-btn" onclick="shareDevice('${name}')">Поделиться</button>
      <button class="dev-btn danger" onclick="removeDevice(this)">✕</button>
    </div>
  `;

        list.appendChild(el);

        // очистка
        document.getElementById('deviceNameInput').value = '';

        closeDeviceModal();

        // Laravel API
        // fetch('/api/devices', ...)
    }
</script>
{{--    @include('cabinet.partials.header')--}}

{{--    <main class="container my-4">--}}
{{--        @yield('content')--}}
{{--    </main>--}}

{{--    @include('cabinet.partials.footer')--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
