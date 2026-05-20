import '../css/app.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import axios from 'axios';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import Home from './pages/Home.vue';

let token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

for (const [key, value] of Object.entries(window.LogViewer.headers || {})) {
  axios.defaults.headers.common[key] = value;
}

const routePath = window.LogViewer.path;
const isRoot = routePath === '' || routePath === '/';

window.LogViewer.basePath = isRoot ? '' : '/' + routePath;

const routerBase = isRoot ? '/' : '/' + routePath + '/';

const router = createRouter({
  routes: [{
    path: '/',
    name: 'home',
    component: Home,
  }],
  history: createWebHistory(routerBase),
});
const pinia = createPinia();

const app = createApp(App);

app.use(router);
app.use(pinia);
app.mixin({
  computed: {
    LogViewer: () => window.LogViewer,
  },
});

app.mount('#log-viewer');
