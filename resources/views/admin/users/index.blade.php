@extends('layouts.admin')
@section('content')
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Добавить</a>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Имя</th>
                    <th>email</th>
                    <th>telegram_id</th>
                    <th>created_at</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $item)
                    <tr class="align-middle">
                        <td>{{$item->id}}</td>
                        <td>
                            <a href="{{ route('admin.devises.index', $item->id) }}">
                                {{$item->name}}
                            </a>
                        </td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->telegram_id}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <a href="{{route('admin.users.edit', $item)}}">Edit</a>
                            <form action="{{ route('admin.users.destroy', $item) }}" method="post">
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