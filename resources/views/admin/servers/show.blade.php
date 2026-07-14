@extends('layouts.admin')
@section('content')
    <a href="{{route('admin.servers.index')}}" class="btn btn-warning">Назад</a>
    <a href="{{route('admin.servers.edit',$server)}}" class="btn btn-success">Редактировать</a>
    <a href="{{route('admin.servers.resyncuser',$server)}}" class="btn btn-primary">Обновить клиентов</a>
    <a href="{{route('admin.server.updateconnect', $server)}}" class="btn btn-success">Обновить подключения</a>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body"><b>id:</b> {{$server->id}}</div>
                    <div class="card-body"><b>name:</b> {{$server->name}}</div>
                    <div class="card-body"><b>type:</b> {{$server->type}}</div>
                    <div class="card-body"><b>ip:</b> {{$server->ip}}</div>
                    <div class="card-body"><b>port:</b> {{$server->port}}</div>
                    <div class="card-body"><b>folder:</b> {{$server->folder}}</div>
                    <div class="card-body"><b>status:</b> {{$server->status}}</div>
                    <div class="card-body"><b>flag:</b> {{$server->flag}}</div>
                    <div class="card-body"><b>login:</b> {{$server->login}}</div>
                    <div class="card-body"><b>password:</b> {{$server->password}}</div>
                    <div class="card-body"><b>token:</b> {{$server->token}}</div>
                    <div class="card-body"><b>priority:</b> {{$server->priority}}</div>
                    <div class="card-body"><b>created_at:</b> {{$server->created_at->format('d.m.Y H:i')}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
