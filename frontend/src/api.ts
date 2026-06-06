import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export const authAPI = {
  register: (data: { name: string; email: string; password: string; role?: string }) =>
    api.post('/register', data),
  login: (data: { email: string; password: string }) => api.post('/login', data),
  logout: () => api.post('/logout'),
  me: () => api.get('/me'),
};

export const transactionAPI = {
  getAll: (params?: any) => api.get('/transactions', { params }),
  getById: (id: number) => api.get(`/transactions/${id}`),
  create: (data: any) => api.post('/transactions', data),
  update: (id: number, data: any) => api.put(`/transactions/${id}`, data),
  delete: (id: number) => api.delete(`/transactions/${id}`),
  getStatistics: () => api.get('/transactions/statistics'),
  getAlerts: () => api.get('/transactions/alerts'),
};

export default api;
