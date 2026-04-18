@extends('layouts.admin')
@section('content')
    <a href="{{route('admin.devises.index', $user)}}" class="btn btn-warning">Назад</a>
    <div class="card-body">
        <form action="{{ isset($devise) ? route('admin.devises.update', $user) : route('admin.devises.store', $user) }}"
            method="post">
            @csrf

            @if(isset($devise))
                @method('PUT')
            @endif

            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">{{ isset($devise) ? 'Редактировать устройство' : 'Добавить устройство' }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input name="name" class="form-control" value="{{ old('name', $devise->name ?? '') }}">
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{isset($devise) ? 'Изменить' : 'Добавить'}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection