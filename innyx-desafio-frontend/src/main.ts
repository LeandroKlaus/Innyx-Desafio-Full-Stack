import { createApp } from 'vue'
import { createPinia } from 'pinia'
import 'vuetify/styles'
import { createVuetify, type ThemeDefinition } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@mdi/font/css/materialdesignicons.css'
import App from './App.vue'
import router from './router'

const innyxTheme: ThemeDefinition = {
  dark: false,
  colors: {
    background: '#FFFFFF',
    surface: '#FFFFFF',
    primary: '#673AB7',
    'primary-darken-1': '#512DA8',
    secondary: '#4CAF50',
    'secondary-darken-1': '#388E3C',
    error: '#B00020',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FB8C00',
  },
}

const app = createApp(App)

const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'innyxTheme',
    themes: {
      innyxTheme,
    },
  },
})

app.use(createPinia())
app.use(router)
app.use(vuetify)

app.mount('#app')