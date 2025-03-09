import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import '@fortawesome/fontawesome-free/css/all.min.css';
import "toastr/build/toastr.min.css";
import '@/assets/scss/main.scss'

const app = createApp(App)
app.use(router)
app.mount('#app')