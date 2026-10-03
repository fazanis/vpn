@extends('cabinet.layouts.app')

@section('content')
    <div class="card mb-4">

        <div class="card-title">
            <span>Тарифы</span>
        </div>

        <div class="tarifs-list">
            @foreach($tarifs as $tarif)
                <div class="tarif-item">
                    <div class="tarif-info">
                        <div class="tarif-name">
                            {{ $tarif->name }}
                        </div>
                        <div class="tarif-name">
                            @plural($tarif->duration_days, 'день')
                        </div>
                        <div class="tarif-name">
                            @plural($tarif->max_devices, 'устройство')
                        </div>
                    </div>

                    <div class="tarif-price">
                        {{ $tarif->price }} ₽
                    </div>

                    <div class="tarif-action">
                        @if($tarif->is_trial)
                            <a href="{{route('cabinet.subscription.trial.activate',$tarif)}}"
                               class="dev-btn warning">
                                <i class="bi bi-gift"></i>
                                Активировать
                            </a>
                        @else
                            <form action="{{ route('cabinet.subscription.buy', [$tarif,$user]) }}" method="POST">
                                @csrf

                                <button type="submit" class="dev-btn success">
                                    <i class="bi bi-credit-card"></i>
                                    Купить
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@endsection
