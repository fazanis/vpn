@extends('cabinet.layouts.app')

@section('content')

    <div class="card text-center p-4">
        <h3 id="payment-title">Проверяем оплату...</h3>

        <p id="payment-message">
            Пожалуйста, подождите. Мы проверяем статус платежа.
        </p>

        <div class="spinner-border mx-auto my-3"
             id="payment-loader"></div>

        <a href="{{ route('cabinet.dashboard') }}"
           id="cabinet-link"
           class="btn btn-primary d-none">
            Перейти в кабинет
        </a>
    </div>

    <script>
        const statusUrl = @json(route('payment.status', $payment));
        const title = document.getElementById('payment-title');
        const message = document.getElementById('payment-message');
        const loader = document.getElementById('payment-loader');
        const cabinetLink = document.getElementById('cabinet-link');

        async function checkPayment() {
            try {
                const response = await fetch(statusUrl, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'paid') {
                    title.textContent = 'Оплата прошла успешно!';
                    message.textContent = 'Ваша подписка активирована.';
                    loader.classList.add('d-none');
                    cabinetLink.classList.remove('d-none');
                    return;
                }

                if (data.status === 'failed') {
                    title.textContent = 'Оплата не прошла';
                    message.textContent = 'Попробуйте повторить оплату.';
                    loader.classList.add('d-none');
                    cabinetLink.classList.remove('d-none');
                    return;
                }

                setTimeout(checkPayment, 3000);

            } catch (error) {
                setTimeout(checkPayment, 5000);
            }
        }

        checkPayment();
    </script>
@endsection
