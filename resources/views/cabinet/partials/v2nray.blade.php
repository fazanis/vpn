<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h5>Установка</h5>
        <select id="platformSelectV2ray" name="platformv2" class="form-control" style="width:120px; float: right;">
            <option value="Android" {{ $platform == 'Android' ? 'selected' : '' }}>Android</option>
            <option value="Windows" {{ $platform == 'Windows' ? 'selected' : '' }}>Windows</option>
        </select>

    </div>
</div>
<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h2>1. Установка приложения</h2>
        <p>Выберите подходящую версию для вашего устройства, нажмите на кнопку ниже и установите приложение.</p>
        <a id="v2downloadLink" href="#" target="_blank">Скачать приложение</a>
    </div>
</div>
<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h2>2. Добавление подписки</h2>
        <p>Нажмите кнопку ниже — Откройте приложение v2nray и вставьте из буфера обмена.</p>
        <button onclick="copyLink()" class="btn btn-success">Копировать ссылку</button>

    </div>
</div>
<div class="col-md-12 mx-auto mb-3">
    <div class="card p-3">
        <h2>3. Подключение и использование</h2>
        <p>В главном разделе нажмите большую кнопку включения в центре для подключения к VPN. Не забудьте выбрать
            сервер в списке серверов. При необходимости выберите другой сервер из списка серверов.</p>
    </div>
</div>

<script>
    function copyLink() {
        const url = window.location.href;

        navigator.clipboard.writeText(url).then(() => {
            alert('Ссылка скопирована!');
        }).catch(err => {
            console.error('Ошибка копирования: ', err);
        });
    }
</script>