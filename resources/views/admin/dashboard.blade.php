@extends('layouts.admin')
@section('content')
    {{ env('APP_URL') . '/cabinet/login?ref=' . auth()->user()->referral_code }}

        <div class="container-fluid mb-3">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5 class="mb-0">
                            Всего онлайн: {{ $total ?? collect($online)->sum('count') }}
                        </h5>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($online as $item)
                    <div class="col-md-6">
                        <div class="card card-outline
                        {{ $item['count'] > 0 ? 'card-success' : 'card-secondary' }}">

                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ $item['sertverIp'] }}
                                </h3>

                                <div class="card-tools">
                                <span class="badge
                                    {{ $item['count'] > 0 ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $item['count'] }} online
                                </span>
                                </div>
                            </div>

                            <div class="card-body p-2">
                                @if(!empty($item['users']))
                                    <ul class="list-group list-group-flush">
                                        @foreach($item['users'] as $user)
                                            <li class="list-group-item">
                                                <i class="fas fa-user text-primary mr-2"></i>
                                                {{ $user }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="text-center text-muted p-3">
                                        Нет активных пользователей
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
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
                        <th>link</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($servers as $item)
                        <tr>
                            <td>{{$item->serverName}}</td>
                            <td>{{$item->serverIp}}</td>
                            <td>{{$item->cpu}} %</td>
                            <td>
                                <div class="progress" style="height: 15px;">
                                    <div
                                        class="progress-bar {{ $item->memoryPercentFormated() > 80 ? 'bg-danger' : ($item->memoryPercentFormated() > 50 ? 'bg-warning' : 'bg-success') }}"
                                        style="width: {{ $item->memoryPercentFormated() }}%">
                                    </div>
                                </div>
                                <small>{{$item->memCurrentFormated()}}</small>
                            </td>

                            <td>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $item->diskPercentFormated() }}%">
                                    </div>
                                </div>
                                <small>{{ $item->diskCurrentFormated() }}</small>
{{--                                {{$item->diskCurrentFormated()}} {{$item->diskTotalFormated()}}--}}
                            </td>
                            <td>
                                ↑ {{$item->netIOUp}}  ↓ {{$item->netIODown}}
                            </td>
                            <td>
                                {{$item->xray}}
                            </td>
                            <td>
                                {{$item->uptime()}}
                            </td>
                            <td>
                                <a href="{{$item->serverLink()}}" target="_blank">Перейти</a>

                            </td>
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
                        <th scope="col">Пользователь</th>
                        <th scope="col">Устройство</th>
                        <th scope="col">Скачано</th>
                        <th scope="col">ui_id</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($devises as $devise)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $devise->user->name }}</td>
                            <td>{{ $devise->name }}</td>
                            <td>{{ formatBytes($devise->trafik) }}</td>
                            <td>{{ $devise->ui_id }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{ $devises->links() }}
            </ul>
        </div>
{{--        <div class="card-footer clearfix">--}}
{{--            {{ $devises->links() }}--}}
{{--        </div>--}}
    </div>
@endsection
