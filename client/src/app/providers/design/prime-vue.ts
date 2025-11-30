import PrimeVue from 'primevue/config'
import Aura from '@primeuix/themes/aura'
import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice'
import type {App} from 'vue'

export function setupPrimeVue(app: App) {
  app.use(PrimeVue, {
    theme: {
      preset: Aura,
      options: {
        // darkModeSelector: '.dark',
      }
    },
    locale: {}
  })

  app.use(ToastService)
  app.use(ConfirmationService)
}
