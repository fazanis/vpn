@extends('layouts.admin')
@section('content')
    <a href="{{route('admin.servers.create')}}" class="btn btn-success">Добавить</a>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>name</th>
                    <th>imbounds</th>
                    <th>Тип</th>
                    <th>ip</th>
                    <th>port</th>
                    <th>folder</th>
                    <th>status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($servers as $item)
                    <tr class="align-middle">
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>
                            <a href="{{ route('admin.server_inbounds.index', $item) }}">
                                {{$item->inbounds()->count()}}
                            </a>
                        </td>
                        <td>{{$item->type}}</td>
                        <td>{{$item->ip}}</td>
                        <td>{{$item->port}}</td>
                        <td>{{$item->folder}}</td>
                        <td>{{$item->priority}}</td>
                        <td>{{$item->status}}
                            <a href="{{route('admin.server.deactivated', $item)}}"
                                class="btn {{$item->status === 'active' ? 'btn-success' : 'btn-danger' }}">
                                {{$item->status === 'active' ? 'Отключить' : 'Включить'}}
                            </a>
                        </td>
                        <td>
{{--                            <a href="{{route('admin.servers.addInbound', $item)}}" class="btn btn-success">--}}
{{--                                Настроить--}}
{{--                            </a>--}}
                            <a href="{{route('admin.server.updateconnect',$item)}}"><i class="fa fa-undo" aria-hidden="true"></i></a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{route('admin.servers.show', $item)}}"><i class="fa fa-eye"></i></a>
                                <a href="{{route('admin.servers.edit', $item)}}"><i class="fa fa-edit"></i></a>
                            <form action="{{ route('admin.servers.destroy', $item) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Вы уверенны?')">
                                    <i class="fa fa-trash text-danger"></i>
                                </button>
                            </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
