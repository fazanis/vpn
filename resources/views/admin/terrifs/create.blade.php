@extends('layouts.admin')
@section('content')
    <a href="{{route('admin.tarrifs.index')}}" class="btn btn-warning">Назад</a>
    <div class="card-body">
        <form action="{{ isset($tarrif) ? route('admin.tarrifs.update', $tarrif) : route('admin.tarrifs.store') }}"
            method="post">
            @csrf

            @if(isset($tarrif))
                @method('PUT')
            @endif

            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title">{{ isset($tarrif) ? 'Редактировать тариф' : 'Добавить тариф' }}</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input name="name" class="form-control" value="{{ old('name', $tarrif->name ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Цена</label>
                        <input name="price" class="form-control" value="{{ old('price', $tarrif->price ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Треал</label>
                        <input type="checkbox" name="is_trial" value="1" {{ old('is_trial', $tarrif->is_trial ?? false) ? 'checked' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Колличество дней подписки</label>
                        <input name="duration_days" class="form-control"
                            value="{{ old('duration_days', $tarrif->duration_days ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Колличество устройств</label>
                        <input name="max_devices" type="max_devices" class="form-control"
                            value="{{ old('max_devices', $tarrif->max_devices ?? '') }}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{isset($server) ? 'Изменить' : 'Добавить'}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection