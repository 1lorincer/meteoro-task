import {createApp} from 'vue'
import {ToastService} from "primevue";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import App from "@/app/App.vue";
import {routerConfig} from "@/app/providers/router";
import {setupPinia} from "@/app/providers/store";
import "@/app/styles/base.css"

const app = createApp(App)

app.use(routerConfig)
app.use(PrimeVue, {
  theme: {
    preset: Aura
  }
});
app.use(ToastService)
setupPinia(app)


app.mount('#app')
