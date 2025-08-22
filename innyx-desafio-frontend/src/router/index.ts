import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import LoginView from '../views/LoginView.vue';
import ProductsListView from '../views/ProductsListView.vue';
import ProductFormView from '../views/ProductFormView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/',
      name: 'products',
      component: ProductsListView,
      meta: { requiresAuth: true } // Esta rota precisa de autenticação
    },
    {
      path: '/produtos/novo',
      name: 'product-new',
      component: ProductFormView,
      meta: { requiresAuth: true }
    },
    {
      path: '/produtos/editar/:id',
      name: 'product-edit',
      component: ProductFormView,
      meta: { requiresAuth: true }
    }
  ]
});

// Guarda de Navegação Global
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const requiresAuth = to.meta.requiresAuth;

  // Se a rota exige autenticação e o usuário não está logado
  if (requiresAuth && !authStore.isAuthenticated) {
    // Redireciona para a página de login
    next({ name: 'login' });
  } else if (to.name === 'login' && authStore.isAuthenticated) {
    // Se o usuário já está logado e tenta acessar a página de login
    // Redireciona para a home
    next({ name: 'products' });
  } else {
    // Permite o acesso
    next();
  }
});

export default router;
