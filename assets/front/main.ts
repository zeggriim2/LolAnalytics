import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import '@shared/tailwind.css'

const app = createApp(App)

app.use(router)

app.mount('#front-app')
