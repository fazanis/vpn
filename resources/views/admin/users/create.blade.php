@extends('layouts.admin')
@section('content')
    <a href="{{route('admin.users.index')}}" class="btn btn-warning">Назад</a>
    <div class="card-body">
        <form action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" method="post">
            @csrf

            @if(isset($user))
                @method('PUT')
            @endif

            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">{{ isset($user) ? 'Редактировать пользователя' : 'Добавить пользователя' }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
                    </div>
                    {{-- <div class="mb-3">
                        <label class="form-label">imbound</label>
                        <input name="imbound" class="form-control" value="{{ old('imbound', $user->imbound ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">url</label>
                        <input name="url" class="form-control" value="{{ old('url', $user->url ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">login</label>
                        <input name="login" class="form-control" value="{{ old('login', $user->login ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">password</label>
                        <input name="password" type="password" class="form-control"
                            value="{{ old('password', $user->password ?? '') }}">
                    </div> --}}
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{isset($user) ? 'Изменить' : 'Добавить'}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection