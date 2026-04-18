@extends('layouts.admin')
@section('content')

    <a href="{{ route('admin.devises.create', $user) }}" class="btn btn-success">Добавить</a>
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
                @foreach($devises as $item)

                    <tr class="align-middle">
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->ui_name}}</td>
                        <td>{{$item->ui_id}}</td>
                        <td>
                            <a href="{{route('subscription.devises', $item->ui_id)}}">Поделиться</a>
                            <a href="{{route('admin.devises.edit', $item)}}">Edit</a>
                            {{-- <form action="{{ route('admin.users.destroy', $item) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form> --}}

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection