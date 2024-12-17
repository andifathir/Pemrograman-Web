import { createApp } from 'vue'
import router from './router'
import App from './App.vue'
import './assets/js/script.js'
import './assets/css/style.css'
import 'swiper/swiper-bundle.css';

createApp(App).use(router).mount('#app')