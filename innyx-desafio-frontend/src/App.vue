<script setup lang="ts">
import { RouterView, useRouter } from 'vue-router'
import { useAuthStore } from './stores/auth';
import { computed } from 'vue';

const authStore = useAuthStore();
const router = useRouter();

const isAuthenticated = computed(() => authStore.isAuthenticated);
const userName = computed(() => authStore.user?.name);

function handleLogout() {
  authStore.logout();
}

function goToHome() {
  if (isAuthenticated.value) {
    router.push('/');
  }
}
</script>

<template>
  <v-app>
    <v-app-bar app color="primary" dark elevation="2">
      <v-toolbar-title class="font-weight-bold" style="cursor: pointer;" @click="goToHome">
        INNYX Gestão de Produtos
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <div v-if="isAuthenticated" class="d-flex align-center pr-4">
        <span class="mr-2 d-none d-sm-flex">Olá, {{ userName }}</span>
        <v-tooltip text="Sair do sistema">
          <template v-slot:activator="{ props }">
            <v-btn v-bind="props" icon @click="handleLogout">
              <v-icon>mdi-logout</v-icon>
            </v-btn>
          </template>
        </v-tooltip>
      </div>
    </v-app-bar>
    <v-main class="bg-grey-lighten-4">
      <RouterView />
    </v-main>
  </v-app>
</template>

<style scoped>
.d-sm-flex {
  display: flex !important;
}
@media (max-width: 600px) {
  .d-none.d-sm-flex {
    display: none !important;
  }
}
</style>