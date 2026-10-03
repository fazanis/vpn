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
    <link rel="stylesheet" href="{{ asset('/css/cabinet.css') }}">
</head>

<body>
<div class="mobile-overlay" id="mobileOverlay"></div>

<div class="shell">

    <aside class="sidebar" id="sidebar">
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

            <a href="{{route('cabinet.dashboard')}}" class="nav-item {{ request()->routeIs('cabinet.dashboard') ? 'active' : '' }}">
{{--            <div class="nav-item">--}}
                <svg viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.3" />
                    <path d="M8 2C8 2 6 5 6 8s2 6 2 6M8 2c0 0 2 3 2 6s-2 6-2 6M2 8h12"
                          stroke="currentColor"
                          stroke-width="1.3" />
                </svg>
                <span>Обзор</span>
{{--            </div>--}}
            </a>
            <a href="{{ route('cabinet.tarifs') }}"
               class="nav-item {{ request()->routeIs('cabinet.tarifs') ? 'active' : '' }}">
                <svg viewBox="0 0 16 16" fill="none">
                    <rect x="2" y="2" width="12" height="10" rx="2"
                          stroke="currentColor"
                          stroke-width="1.3" />
                    <path d="M5 14h6M8 12v2"
                          stroke="currentColor"
                          stroke-width="1.3" />
                </svg>
                <span>Тарифы</span>
            </a>
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

            <button
                type="button"
                class="mobile-menu-button"
                onclick="toggleMobileMenu()"
                aria-label="Меню"
            >
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M4 6H20M4 12H20M4 18H20"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg>
            </button>

            <div>
                <div class="page-title">Личный кабинет</div>
                <div class="page-sub">
                    Добро пожаловать {{auth()->user()->name}}
                </div>
            </div>

        </div>
        @include('cabinet.partials.errors')

        <div class="content-grid">
            <div>
{{--                <div class="card mb-3">--}}

        @yield('content')
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
{{--                                <div class="srv-city">{{$server->protocol}} / {{$server->type}} / {{$server->security}} </div>--}}
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
    function toggleMobileMenu() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');

        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('mobile-open');
    }
    document.getElementById('mobileOverlay').addEventListener('click', function () {
        document.getElementById('sidebar').classList.remove('mobile-open');
        this.classList.remove('mobile-open');
    });
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
