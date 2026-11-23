import './bootstrap';

import Alpine from 'alpinejs';
import { createApp } from 'vue';

window.Alpine = Alpine;

Alpine.start();


const app = createApp({});

// Регистрируем компоненты
import ServerOnlineComponent from './components/ServerOnlineComponent.vue';
import UserOnlineComponent from './components/UserOnlineComponent.vue';

app.component('server-online-component', ServerOnlineComponent);
app.component('user-online-component', UserOnlineComponent);

// Запускаем Vue
const vueElement = document.getElementById('vue-app');

if (vueElement) {
    app.mount(vueElement);
}
