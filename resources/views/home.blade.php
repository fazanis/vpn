@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <a href="/" class="btn btn-success">
        <i class="fas fa-plus"></i> Добавить
    </a>
    <x-adminlte-datatable id="table7" :heads="$heads" head-theme="dark" >
        @foreach($users as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>
                    <a href="" class="btn btn-success">edit</a>
                </td>
            </tr>
        @endforeach
    </x-adminlte-datatable>

    @section('plugins.Datatables', true)
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
