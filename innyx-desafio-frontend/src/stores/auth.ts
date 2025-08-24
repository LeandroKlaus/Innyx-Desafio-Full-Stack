import { defineStore } from 'pinia';
import api from '@/services/api';
import router from '@/router';

const storedToken = localStorage.getItem('token');
const storedUser = localStorage.getItem('user');

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: storedToken || null as string | null,
    user: storedUser ? JSON.parse(storedUser) : null as any | null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(credentials: any) {
      try {
        const response = await api.post('/login', credentials);
        this.setAuth(response.data);
        router.push('/');
      } catch (error) {
        console.error('Falha no login:', error);
        throw error;
      }
    },

    async register(userData: any) {
        try {
          const response = await api.post('/register', userData);
          this.setAuth(response.data);
          router.push('/');
        } catch (error) {
          console.error('Falha no registro:', error);
          throw error;
        }
    },

    logout() {
      this.clearAuth();
      router.push('/login');
    },

    setAuth(data: any) {
      this.token = data.access_token;
      this.user = data.user;
      localStorage.setItem('token', data.access_token);
      localStorage.setItem('user', JSON.stringify(data.user));
    },

    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  },
});