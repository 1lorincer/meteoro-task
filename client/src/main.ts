import {createApp} from 'vue'
import App from "@/app/App.vue";
import {routerConfig} from "@/app/providers/router";
import {setupPinia} from "@/app/providers/store";
import {setupPrimeVue} from "@/app/providers/design/prime-vue.ts";
import "@/app/styles/base.css"

const app = createApp(App)

app.use(routerConfig)
setupPrimeVue(app)
setupPinia(app)


app.mount('#app')
