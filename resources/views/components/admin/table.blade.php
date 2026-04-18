@props([
    'name'=>'Таблица',
    'th'=>[],
    'data'=>null
])
<div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $name }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" role="table">
                    <thead>
                        <tr>
                            @foreach ($th ?? [] as $head)
                                <th scope="col">{{ $head }}</th>
                            @endforeach
                            
                        </tr>
                    </thead>
                    <tbody>
                            {{ $slot ?? '' }}
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $data ? $data->links() : '' }}
        </div>
    </div>