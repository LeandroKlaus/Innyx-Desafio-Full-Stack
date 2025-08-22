import { createApp } from 'vue'
import { createPinia } from 'pinia'

// Vuetify
import 'vuetify/styles'
import { createVuetify, type ThemeDefinition } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
// A LINHA MAIS IMPORTANTE PARA OS ÍCONES É ESTA ABAIXO:
import '@mdi/font/css/materialdesignicons.css' 

import App from './App.vue'
import router from './router'

// Definição do tema customizado INNYX
const innyxTheme: ThemeDefinition = {
  dark: false,
  colors: {
    background: '#FFFFFF',
    surface: '#FFFFFF',
    primary: '#673AB7',    // Roxo principal
    'primary-darken-1': '#512DA8',
    secondary: '#4CAF50',  // Verde secundário
    'secondary-darken-1': '#388E3C',
    error: '#B00020',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FB8C00',
  },
}

const app = createApp(App)

// Crie a instância do Vuetify com o tema
const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'innyxTheme', // Define nosso tema como padrão
    themes: {
      innyxTheme, // Registra o tema
    },
  },
})

app.use(createPinia())
app.use(router)
app.use(vuetify)

app.mount('#app')