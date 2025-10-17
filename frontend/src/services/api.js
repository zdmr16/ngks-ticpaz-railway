import axios from 'axios';
import toast from 'react-hot-toast';

// API base URL - Environment based
const API_BASE_URL = import.meta.env.PROD
  ? 'https://ngks-ticpaz-railway-production.up.railway.app/api'
  : 'http://localhost:8001/api';

// Axios instance oluştur
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Request interceptor - JWT token'ı otomatik ekle
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor - Hata yönetimi
api.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      // Token geçersiz - kullanıcıyı logout et
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      window.location.href = '/login';
      toast.error('Oturum süreniz doldu. Lütfen tekrar giriş yapın.');
    } else if (error.response?.status === 500) {
      toast.error('Sunucu hatası. Lütfen daha sonra tekrar deneyin.');
    } else if (error.response?.data?.message) {
      toast.error(error.response.data.message);
    } else {
      toast.error('Bir hata oluştu. Lütfen tekrar deneyin.');
    }
    
    return Promise.reject(error);
  }
);

// API Service Functions

// Bayiler API
export const bayilerService = {
  // Tüm bayileri getir
  getAll: () => api.get('/bayiler'),
  
  // Belirli bir bayiyi getir
  getById: (id) => api.get(`/bayiler/${id}`),
  
  // Yeni bayi oluştur
  create: (data) => api.post('/bayiler', data),
  
  // Bayi güncelle
  update: (id, data) => api.put(`/bayiler/${id}`, data),
  
  // Bayi sil
  delete: (id) => api.delete(`/bayiler/${id}`),
  
  // Bayi çalışanlarını getir
  getCalisanlar: (id) => api.get(`/bayiler/${id}/calisanlar`),
  
  // Bayi çalışanı ekle
  addCalisan: (id, data) => api.post(`/bayiler/${id}/calisanlar`, data),
  
  // Bayi çalışanı güncelle
  updateCalisan: (id, calisanId, data) => api.put(`/bayiler/${id}/calisanlar/${calisanId}`, data),
  
  // Bayi çalışanı sil
  removeCalisan: (id, calisanId) => api.delete(`/bayiler/${id}/calisanlar/${calisanId}`),
  
  // Bayi mağazalarını getir
  getMagazalar: (id) => api.get(`/bayiler/${id}/magazalar`),
  
  // Bayi mağazası ekle
  addMagaza: (id, data) => api.post(`/bayiler/${id}/magazalar`, data),
  
  // Bayi mağazası güncelle
  updateMagaza: (id, magazaId, data) => api.put(`/bayiler/${id}/magazalar/${magazaId}`, data),
  
  // Bayi mağazası sil
  deleteMagaza: (id, magazaId) => api.delete(`/bayiler/${id}/magazalar/${magazaId}`)
};

// Bölge Mimarları API
export const bolgeMimarlariService = {
  // Tüm bölge mimarlarını getir
  getAll: () => api.get('/bolge-mimarlari'),
  
  // Belirli bir bölge mimarını getir
  getById: (id) => api.get(`/bolge-mimarlari/${id}`),
  
  // Yeni bölge mimarı oluştur
  create: (data) => api.post('/bolge-mimarlari', data),
  
  // Bölge mimarı güncelle
  update: (id, data) => api.put(`/bolge-mimarlari/${id}`, data),
  
  // Bölge mimarı sil
  delete: (id) => api.delete(`/bolge-mimarlari/${id}`),
  
  // Mimar atamalarını getir
  getAtamalar: (id) => api.get(`/bolge-mimarlari/${id}/atamalar`),
  
  // Bölge ataması ekle
  addAtama: (id, data) => api.post(`/bolge-mimarlari/${id}/atamalar`, data),
  
  // Bölge atamasını sil
  removeAtama: (id, atamaId) => api.delete(`/bolge-mimarlari/${id}/atamalar/${atamaId}`),
  
  // Bölgeye göre mimarları getir
  getByBolge: (bolgeId) => api.get(`/bolge-mimarlari/bolge/${bolgeId}`)
};

// Lokasyon API (mevcut endpoint'leri genişlet)
export const locationService = {
  // Bölgeler
  getBolgeler: () => api.get('/locations/bolgeler'),
  getBolgeDetay: (id) => api.get(`/locations/bolgeler/${id}`),
  getBolgeSehirleri: (id) => api.get(`/locations/bolgeler/${id}/sehirler`),
  
  // Şehirler
  getSehirler: () => api.get('/locations/sehirler'),
  getSehirDetay: (id) => api.get(`/locations/sehirler/${id}`),
  getSehirIlceleri: (id) => api.get(`/locations/sehirler/${id}/ilceler`),
  
  // İlçeler
  getIlceler: () => api.get('/locations/ilceler'),
  
  // Hiyerarşi
  getHiyerarsi: () => api.get('/locations/hiyerarsi')
};

export default api;