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
      meta: { requiresAuth: true }
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

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const requiresAuth = to.meta.requiresAuth;

  if (requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' });
  } else if (to.name === 'login' && authStore.isAuthenticated) {
    next({ name: 'products' });
  } else {
    next();
  }
});

export default router;