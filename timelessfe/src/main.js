import { createApp } from 'vue'
import router from './router'
import './assets/js/script.js'
import './assets/css/style.css'
import App from './App.vue'
import 'swiper/swiper-bundle.css';

createApp(App).use(router).mount('#app')