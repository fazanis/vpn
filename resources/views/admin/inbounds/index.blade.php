@extends('layouts.admin')

@section('content')
    <a href="{{ route('admin.servers.index') }}" class="btn btn-warning">Назад</a>
    <x-admin.table name="Подключения" :th="[
            'Название',
            'inbound',
            'port',
            'protocol',
            'type',
            'encryption',
            'security',
            'pbk',
            'fp',
            'sni',
            'sid',
            'spx',
            'path',
            'host',
            'mode',
        ]" :data="$inbounds">
        @foreach ($inbounds as $server)
            <tr>
                <td>{{ $server->server->name }}</td>
                <td>{{ $server->inbound }}</td>
                <td>{{ $server->port }}</td>
                <td>{{ $server->protocol }}</td>
                <td>{{ $server->type }}</td>
                <td>{{ $server->encryption }}</td>
                <td>{{ $server->security }}</td>
                <td>{{ $server->fp }}</td>
                <td>{{ $server->spx }}</td>
                <td>{{ $server->path }}</td>
                <td>{{ $server->host }}</td>
                <td>{{ $server->mode }}</td>
            </tr>
        @endforeach

    </x-admin.table>


@endsection