@extends('layouts.admin')
@section('content')
    {{ env('APP_URL') . '/cabinet/login?ref=' . auth()->user()->referral_code }}




                <div id="vue-app">
                <user-online-component></user-online-component>
                <server-online-component></server-online-component>
                </div>





    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Подключения</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{route('admin.reset.traffik')}}" class="btn btn-danger">Сбросить трафик</a>
            <div class="table-responsive">
                <table class="table table-bordered" role="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Устройство</th>
                        <th scope="col">Скачано</th>
                        <th scope="col">ui_id</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($devises as $devise)
                        <tr class="align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $devise->user->name }}</td>
                            <td>{{ $devise->name }}</td>
                            <td>{{ formatBytes($devise->trafik) }}</td>
                            <td>{{ $devise->ui_id }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{ $devises->links() }}
            </ul>
        </div>
{{--        <div class="card-footer clearfix">--}}
{{--            {{ $devises->links() }}--}}
{{--        </div>--}}
    </div>
@endsection
