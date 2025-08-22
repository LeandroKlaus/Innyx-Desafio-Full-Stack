import { defineStore } from 'pinia';
import api from '@/services/api';
import router from '@/router';

// Buscamos o token/usuário do localStorage para manter o login após recarregar a página
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
        router.push('/'); // Redireciona para a lista de produtos
      } catch (error) {
        console.error('Falha no login:', error);
        throw error; // Propaga o erro para o componente de login tratar
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
      // Idealmente, chamaríamos a rota de logout da API também
      // await api.post('/logout');

      this.clearAuth();
      router.push('/login');
    },

    // Ação auxiliar para salvar os dados de autenticação
    setAuth(data: any) {
      this.token = data.access_token;
      this.user = data.user;
      localStorage.setItem('token', data.access_token);
      localStorage.setItem('user', JSON.stringify(data.user));
    },

    // Ação auxiliar para limpar os dados de autenticação
    clearAuth() {
      this.token = null;
      this.user = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  },
});