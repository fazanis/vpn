@extends('layouts.admin')
@section('content')
    <a href="{{route('admin.servers.index')}}" class="btn btn-warning">Назад</a>
    <div class="card-body">
        <form action="{{ isset($server) ? route('admin.servers.update', $server) : route('admin.servers.store') }}"
            method="post">
            @csrf

            @if(isset($server))
                @method('PUT')
            @endif

            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">{{ isset($server) ? 'Редактировать сервер' : 'Добавить сервер' }}</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input name="name" class="form-control" value="{{ old('name', $server->name ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Тип</label>
                        <select name="type" class="form-control">
                            <option value="https" {{ isset($server->type) && $server->type === 'https' ? 'selected' : '' }}>
                                https
                            </option>
                            <option value="http" {{ isset($server->type) && $server->type === 'http' ? 'selected' : '' }}>http
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ip</label>
                        <input name="ip" class="form-control" value="{{ old('ip', $server->ip ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">port</label>
                        <input name="port" class="form-control" value="{{ old('port', $server->port ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">folder</label>
                        <input name="folder" class="form-control" value="{{ old('folder', $server->folder ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">priority</label>
                        <input name="priority" class="form-control" value="{{ old('priority', $server->priority ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">imbound</label>
                        <input name="imbound" class="form-control" value="{{ old('imbound', $server->imbound ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Флаг</label>
                        <input name="flag" class="form-control" value="{{ old('flag', $server->flag ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">login</label>
                        <input name="login" class="form-control" value="{{ old('login', $server->login ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">password</label>
                        <input name="password" type="password" class="form-control"
                            value="{{ old('password', $server->password ?? '') }}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{isset($server) ? 'Изменить' : 'Добавить'}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection