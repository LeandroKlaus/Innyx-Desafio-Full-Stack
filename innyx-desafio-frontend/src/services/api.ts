import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

// URL base da sua API Laravel.
// Lembre-se de rodar `php artisan serve` no seu backend.
const API_URL = 'http://127.0.0.1:8000/api';

const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

// Interceptor: Executa antes de CADA requisição
api.interceptors.request.use(config => {
  const authStore = useAuthStore();
  const token = authStore.token;

  // Se o token existir, adiciona no cabeçalho Authorization
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
}, error => {
  return Promise.reject(error);
});

export default api;