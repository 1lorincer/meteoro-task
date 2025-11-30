import {createApp} from 'vue'
import {ToastService} from "primevue";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import App from "@/app/App.vue";
import {routerConfig} from "@/app/providers/router";
import {setupPinia} from "@/app/providers/store";
import "@/app/styles/base.css"
import {setupPrimeVue} from "@/app/providers/design/prime-vue.ts";

const app = createApp(App)

app.use(routerConfig)
setupPrimeVue(app)
setupPinia(app)


app.mount('#app')
