@extends('layouts.admin')
@section('content')
    {{ env('APP_URL') . '/cabinet/login?ref=' . auth()->user()->referral_code }}
    {{-- <div class="row">
        @foreach($server_response as $server)
        @php $s = $server['obj']; @endphp

        <div class="col-md-3 mb-2">
            <div class="card p-2 small">
                <b>{{ $s['publicIP']['ipv4'] }}</b><br>
                CPU: {{ number_format($s['cpu'], 1) }}%<br>
                RAM: {{ formatBytes($s['mem']['current']) }}<br>
            </div>
        </div>
        @endforeach
    </div> --}}
    <div class="col-md-3 mb-2">
        <div class="card p-2 small">
            <b>Сейчас онлайн
                {{ $online }}
            </b><br>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Сервера</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>IP</th>
                            <th>CPU</th>
                            <th>RAM</th>
                            <th>Disk</th>
                            <th>Net ↑↓</th>
                            <th>Xray</th>
                            <th>Uptime</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($server_response as $item)
                            @php
                                $server = $item['server'];   // модель из БД
                                $s = $item['status']['obj'] ?? null; // данные API
                            @endphp

                            <tr>
                                <td>
                                    <a target="_blank"
                                        href="{{ $server->type . '://' . $server->ip . ':' . $server->port . '/' . $server->folder }}">
                                        {{ $server->name }}
                                    </a>
                                </td>
                                @if(!$s)
                                    <td colspan="7" class="text-danger">
                                        Сервер не в сети
                                    </td>
                                @else
                                    @php
                                        $ramPercent = ($s['mem']['current'] / $s['mem']['total']) * 100;
                                        $diskPercent = ($s['disk']['current'] / $s['disk']['total']) * 100;
                                    @endphp
                                    <td>{{ $s['publicIP']['ipv4'] }}</td>

                                    <td>
                                        <span
                                            class="
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {{ $s['cpu'] > 80 ? 'text-danger' : ($s['cpu'] > 50 ? 'text-warning' : 'text-success') }}">
                                            {{ number_format($s['cpu'], 1) }}%
                                        </span>
                                    </td>

                                    <td style="min-width:150px;">
                                        <div class="progress" style="height: 15px;">
                                            <div class="progress-bar
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {{ $ramPercent > 80 ? 'bg-danger' : ($ramPercent > 50 ? 'bg-warning' : 'bg-success') }}"
                                                style="width: {{ $ramPercent }}%">
                                            </div>
                                        </div>
                                        <small>{{ formatBytes($s['mem']['current']) }}</small>
                                    </td>

                                    <td style="min-width:150px;">
                                        <div class="progress" style="height: 15px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $diskPercent }}%">
                                            </div>
                                        </div>
                                        <small>{{ formatBytes($s['disk']['current']) }}</small>
                                    </td>

                                    <td>
                                        ↑ {{ formatBytes($s['netIO']['up']) }}<br>
                                        ↓ {{ formatBytes($s['netIO']['down']) }}
                                    </td>

                                    <td>
                                        <span class="{{ $s['xray']['state'] === 'running' ? 'text-success' : 'text-danger' }}">
                                            {{ $s['xray']['state'] }}
                                        </span>
                                    </td>

                                    <td>{{ formatUptime($s['uptime']) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Подключения</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" role="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Название</th>
                            <th scope="col">Инбоунд</th>
                            <th scope="col">Порт</th>
                            <th scope="col">Протокол</th>
                            <th scope="col">Тип</th>
                            <th scope="col">Защита</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inbounds as $inbound)
                            <tr class="align-middle">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $inbound->server->name }}</td>
                                <td>{{ $inbound->inbound }}</td>
                                <td>{{ $inbound->port }}</td>
                                <td>{{ $inbound->protocol }}</td>
                                <td>{{ $inbound->type }}</td>
                                <td>{{ $inbound->security }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $inbounds->links() }}
        </div>
    </div>
@endsection