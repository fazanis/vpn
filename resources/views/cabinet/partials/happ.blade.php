<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h5>Установка</h5>
        <select id="platformSelectHapp" name="platform" class="form-control" style="width:120px; float: right;">
            <option value="Android" {{ $platform == 'Android' ? 'selected' : '' }}>Android</option>
            <option value="iOS" {{ $platform == 'iOS' ? 'selected' : '' }}>iOS</option>
            <option value="Windows" {{ $platform == 'Windows' ? 'selected' : '' }}>Windows</option>
        </select>

    </div>
</div>
<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h2>1. Установка приложения</h2>
        <p>Выберите подходящую версию для вашего устройства, нажмите на кнопку ниже и установите приложение.</p>
        <a id="downloadLink" href="#" target="_blank">Скачать приложение</a>
    </div>
</div>
<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h2>2. Добавление подписки</h2>
        <p>Нажмите кнопку ниже — приложение откроется, и подписка добавится автоматически.</p>
        <a href="{{ $happLink }}">+ Добавить подписку</a>
    </div>
</div>
{{-- <div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h2>3. Подключение и использование</h2>
        <p>В главном разделе нажмите большую кнопку включения в центре для подключения к VPN. Не забудьте выбрать
            сервер в списке серверов. При необходимости выберите другой сервер из списка серверов.</p>
        <p style="color:red;">
            Важно!!! Настрой в приложении только те приложения которрые тебе нужны для обхода блокировок.
        </p>
    </div>
</div> --}}