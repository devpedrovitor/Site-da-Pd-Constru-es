import App from '@/App.vue';
import router from '@/router';
import { createApp } from 'vue';
import '@/assets/css/tailwind.css';
import { createPinia } from 'pinia';
import 'vue3-toastify/dist/index.css';
import 'vue-loading-overlay/dist/css/index.css';
import { LoadingPlugin } from 'vue-loading-overlay';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';

const app = createApp(App);

const pinia = createPinia();

pinia.use(piniaPluginPersistedstate);

app.use(pinia);

app.use(LoadingPlugin);

app.use(router);

app.mount('#app');
