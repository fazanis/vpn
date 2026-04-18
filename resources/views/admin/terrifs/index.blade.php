@extends('layouts.admin')
@section('content')
    <a href="{{ route('admin.tarrifs.create') }}" class="btn btn-success">Добавить</a>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Триал</th>
                    <th>Колличество дней</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tarrifs as $item)
                    <tr class="align-middle">
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{$item->is_trial}}</td>
                        <td>{{$item->duration_days}}</td>
                        <td>
                            <a href="{{route('admin.tarrifs.edit', $item)}}">Edit</a>
                            <form action="{{ route('admin.tarrifs.destroy', $item) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection