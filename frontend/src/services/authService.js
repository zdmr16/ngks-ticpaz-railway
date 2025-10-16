import api from './api';
import toast from 'react-hot-toast';

class AuthService {
  // Kullanıcı girişi
  async login(email, password) {
    try {
      const response = await api.post('/auth/login', {
        email,
        password
      });

      if (response.data.success) {
        const { token, kullanici } = response.data.data;
        
        // Token ve kullanıcı bilgilerini localStorage'a kaydet
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(kullanici));
        
        toast.success('Giriş başarılı!');
        return { success: true, user: kullanici, token };
      }
    } catch (error) {
      console.error('Login error:', error);
      return { 
        success: false, 
        message: error.response?.data?.message || 'Giriş başarısız' 
      };
    }
  }

  // Kullanıcı çıkışı
  async logout() {
    try {
      await api.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Her durumda localStorage'ı temizle
      localStorage.removeItem('token');
      localStorage.removeItem('user');
      toast.success('Çıkış yapıldı');
    }
  }

  // Kullanıcı profili getir
  async getProfile() {
    try {
      const response = await api.get('/auth/profile');
      return response.data;
    } catch (error) {
      console.error('Get profile error:', error);
      throw error;
    }
  }

  // Token refresh
  async refreshToken() {
    try {
      const response = await api.post('/auth/refresh');
      if (response.data.success) {
        const { token } = response.data.data;
        localStorage.setItem('token', token);
        return token;
      }
    } catch (error) {
      console.error('Token refresh error:', error);
      this.logout();
      throw error;
    }
  }

  // Kullanıcının giriş yapıp yapmadığını kontrol et
  isAuthenticated() {
    const token = localStorage.getItem('token');
    return !!token;
  }

  // Mevcut kullanıcı bilgilerini getir
  getCurrentUser() {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
  }

  // Token'ı getir
  getToken() {
    return localStorage.getItem('token');
  }
}

export default new AuthService();