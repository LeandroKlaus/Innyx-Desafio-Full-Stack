<script setup lang="ts">
import { ref } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const email = ref('leandro@teste.com');
const password = ref('senha12345');
const errorMessage = ref('');
const loading = ref(false);

async function handleLogin() {
  loading.value = true;
  errorMessage.value = '';
  try {
    await authStore.login({ email: email.value, password: password.value });
  } catch (error) {
    errorMessage.value = 'Email ou senha inválidos. Tente novamente.';
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <v-container class="fill-height">
    <v-row justify="center" align="center">
      <v-col cols="12" sm="8" md="5" lg="4">
        <v-card class="elevation-12">
          <v-toolbar color="primary" dark flat style="position: relative;">
            <span class="login-card-title font-weight-bold text-h6">
              INNYX Gestão de Produtos
            </span>
          </v-toolbar>

          <v-card-text class="pa-6">
            <p class="text-h6 text-center mb-6">Acesse sua conta</p>
            <v-form @submit.prevent="handleLogin">
              <v-text-field
                label="Email"
                name="email"
                prepend-inner-icon="mdi-account"
                type="text"
                v-model="email"
                variant="outlined"
              ></v-text-field>
              <v-text-field
                id="password"
                label="Senha"
                name="password"
                prepend-inner-icon="mdi-lock"
                type="password"
                v-model="password"
                variant="outlined"
                class="mt-2"
              ></v-text-field>
              <v-alert v-if="errorMessage" type="error" dense class="mt-4">
                {{ errorMessage }}
              </v-alert>
            </v-form>
          </v-card-text>
          <v-card-actions class="pa-6 pt-0">
            <v-btn
              :loading="loading"
              @click="handleLogin"
              color="primary"
              variant="elevated"
              size="large"
              block
              class="login-button"
            >
              Entrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<style scoped>
.login-card-title {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  width: 100%;
}

.login-button {
  transition: all 0.3s ease-in-out;
  border: 2px solid transparent;
}

.login-button:hover {
  background-color: white !important;
  color: #673AB7 !important;
  border: 2px solid #673AB7;
}
</style>