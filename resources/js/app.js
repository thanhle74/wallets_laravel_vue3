import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import "toastr/build/toastr.min.css";
import '@/assets/scss/main.scss'

const app = createApp(App)
app.use(router)
app.mount('#app')
