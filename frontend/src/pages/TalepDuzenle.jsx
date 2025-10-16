import React, { useState, useEffect } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import Layout from '../components/Layout';
import api from '../services/api';
import { toast } from 'react-hot-toast';
import { ArrowLeftIcon, ClockIcon, UserIcon, ArchiveBoxIcon, LockClosedIcon } from '@heroicons/react/24/outline';

const TalepDuzenle = () => {
  const navigate = useNavigate();
  const { id } = useParams();
  
  const [talep, setTalep] = useState(null);
  const [asamaGecmisi, setAsamaGecmisi] = useState([]);
  const [formData, setFormData] = useState({
    asama_id: ''
  });

  const [dropdownData, setDropdownData] = useState({
    asamalar: []
  });

  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [archiving, setArchiving] = useState(false);

  // Sayfa yüklendiğinde talep verilerini yükle
  useEffect(() => {
    loadTalepData();
  }, [id]);

  const loadTalepData = async () => {
    try {
      setLoading(true);
      
      const [talepRes, gecmisRes] = await Promise.all([
        api.get(`/talepler/${id}`),
        api.get(`/talepler/${id}/asama-gecmisi`)
      ]);

      const talepData = talepRes.data.data;
      setTalep(talepData);
      setAsamaGecmisi(gecmisRes.data.data || []);

      // Sadece aşama bilgisini form'a doldur
      setFormData({
        asama_id: talepData.guncel_asama?.id || ''
      });

      // Talep türü varsa aşamaları yükle
      if (talepData.talep_turu?.id) {
        loadAsamalar(talepData.talep_turu.id);
      }

    } catch (error) {
      console.error('Talep verileri yüklenirken hata:', error);
      toast.error('Talep verileri yüklenemedi');
      navigate('/talepler');
    } finally {
      setLoading(false);
    }
  };

  const loadAsamalar = async (talepTuruId) => {
    try {
      const response = await api.get(`/asamalar?talep_turu_id=${talepTuruId}`);
      setDropdownData(prev => ({
        ...prev,
        asamalar: response.data.data || []
      }));
    } catch (error) {
      console.error('Aşamalar yüklenirken hata:', error);
      toast.error('Aşamalar yüklenemedi');
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Aşama seçimi kontrolü
    if (!formData.asama_id) {
      toast.error('Lütfen bir aşama seçiniz');
      return;
    }

    // Aşama değişikliği kontrolü
    const asamaDegisti = formData.asama_id !== talep.guncel_asama?.id;
    
    if (!asamaDegisti) {
      toast.info('Aşama zaten seçili durumda');
      return;
    }

    try {
      setSaving(true);
      
      // Aşama açıklaması al
      const aciklama = prompt('Aşama değişikliği için açıklama giriniz:');
      if (!aciklama || aciklama.trim() === '') {
        setSaving(false);
        toast.error('Açıklama zorunludur');
        return;
      }
      
      // Aşama değiştir
      await api.put(`/talepler/${id}/asama`, {
        asama_id: formData.asama_id,
        aciklama: aciklama.trim()
      });
      
      toast.success('Aşama başarıyla değiştirildi');
      
      // Sayfayı yenile - talep verilerini ve aşama geçmişini güncel çek
      await loadTalepData();
      
    } catch (error) {
      console.error('Aşama değiştirme hatası:', error);
      
      if (error.response?.data?.errors) {
        // Validation hataları
        const errors = error.response.data.errors;
        Object.keys(errors).forEach(field => {
          errors[field].forEach(message => {
            toast.error(message);
          });
        });
      } else {
        toast.error(error.response?.data?.message || 'Aşama değiştirilirken hata oluştu');
      }
    } finally {
      setSaving(false);
    }
  };

  const handleArchive = async () => {
    if (!window.confirm('Bu talebi arşivlemek istediğinizden emin misiniz? Arşivlenen talepler sadece arşiv görünümünde listelenecektir.')) {
      return;
    }

    try {
      setArchiving(true);
      
      await api.patch(`/talepler/${id}/arsivle`);
      toast.success('Talep başarıyla arşivlendi');
      navigate('/talepler');
      
    } catch (error) {
      console.error('Talep arşivleme hatası:', error);
      toast.error(error.response?.data?.message || 'Talep arşivlenirken hata oluştu');
    } finally {
      setArchiving(false);
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('tr-TR') + ' ' + date.toLocaleTimeString('tr-TR', {
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  if (loading) {
    return (
      <Layout>
        <div className="flex items-center justify-center min-h-96">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>
      </Layout>
    );
  }

  if (!talep) {
    return (
      <Layout>
        <div className="text-center py-12">
          <h2 className="text-xl font-semibold text-gray-900">Talep bulunamadı</h2>
          <button
            onClick={() => navigate('/talepler')}
            className="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            <ArrowLeftIcon className="h-4 w-4 mr-1" />
            Taleplere Dön
          </button>
        </div>
      </Layout>
    );
  }

  return (
    <Layout>
      <div className="space-y-6">
        {/* Header */}
        <div className="bg-white shadow rounded-lg p-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center space-x-3">
              <button
                onClick={() => navigate('/talepler')}
                className="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                <ArrowLeftIcon className="h-4 w-4 mr-1" />
                Geri
              </button>
              <div>
                <h1 className="text-2xl font-bold text-gray-900">Talep #{talep.id} - Aşama Yönetimi</h1>
                <p className="text-gray-600 mt-1">Bu talepte sadece aşama değişikliği yapılabilir. Diğer bilgiler salt okunurdur.</p>
              </div>
            </div>

            {/* Aşama Yönetimi - Header'da */}
            {!talep.arsivlendi_mi && (
              <div className="flex items-center space-x-4 border-l border-gray-200 pl-4">
                <form onSubmit={handleSubmit} className="flex items-center space-x-3">
                  <div>
                    <label className="block text-xs font-medium text-gray-500 mb-1">
                      Yeni Aşama Seç
                    </label>
                    <select
                      name="asama_id"
                      value={formData.asama_id}
                      onChange={handleInputChange}
                      className="block w-48 border border-gray-300 rounded-md px-3 py-1.5 text-sm shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      required
                    >
                      <option value="">Aşama Seçiniz</option>
                      {dropdownData.asamalar.map(asama => (
                        <option key={asama.id} value={asama.id}>{asama.ad}</option>
                      ))}
                    </select>
                  </div>
                  <button
                    type="submit"
                    className="mt-5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    disabled={saving}
                  >
                    {saving ? 'Değiştiriliyor...' : 'Değiştir'}
                  </button>
                </form>
              </div>
            )}

            <div className="flex items-center space-x-4">
              {!talep.arsivlendi_mi && (
                <button
                  onClick={handleArchive}
                  disabled={archiving}
                  className="inline-flex items-center px-3 py-2 border border-orange-300 shadow-sm text-sm leading-4 font-medium rounded-md text-orange-700 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50"
                >
                  <ArchiveBoxIcon className="h-4 w-4 mr-1" />
                  {archiving ? 'Arşivleniyor...' : 'Arşivle'}
                </button>
              )}
              <div className="text-right">
                <div className="text-sm text-gray-500">Güncel Aşama</div>
                <div className="text-lg font-semibold text-gray-900">{talep.guncel_asama?.ad}</div>
                <div className="text-sm text-gray-500">{formatDate(talep.guncel_asama_tarihi)}</div>
                {talep.arsivlendi_mi && (
                  <div className="mt-1">
                    <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                      Arşivlenmiş
                    </span>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>

        {/* Talep Bilgileri ve Aşama Geçmişi Yan Yana */}
        <div className="bg-white shadow rounded-lg p-6">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {/* Sol Taraf - Talep Bilgileri */}
            <div>
              <div className="flex items-center mb-6">
                <LockClosedIcon className="h-5 w-5 text-gray-400 mr-2" />
                <h2 className="text-lg font-medium text-gray-900">Talep Bilgileri (Salt Okunur)</h2>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {/* Bölge */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Bölge
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.bolge?.ad || '-'}
                  </div>
                </div>

                {/* Bölge Mimarı */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Bölge Mimarı
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.bolge_mimari?.ad_soyad || '-'}
                  </div>
                </div>

                {/* Şehir */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Şehir
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.sehir?.ad || '-'}
                  </div>
                </div>

                {/* İlçe */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    İlçe
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.ilce?.ad || '-'}
                  </div>
                </div>

                {/* Bayi */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Bayi
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.bayi ? `${talep.bayi.ad} - ${talep.bayi.sahip_adi}` : '-'}
                  </div>
                </div>

                {/* Mağaza Tipi */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Mağaza Tipi
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.magaza_tipi === 'kendi_magazasi' ? 'Kendi Mağazası' : 'Tali Bayi'}
                  </div>
                </div>

                {/* Mağaza Adı */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Mağaza Adı
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.magaza_adi || '-'}
                  </div>
                </div>

                {/* Talep Türü */}
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Talep Türü
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600">
                    {talep.talep_turu ? `${talep.talep_turu.ad}` : '-'}
                  </div>
                </div>

                {/* Açıklama - Tam genişlik */}
                <div className="md:col-span-2">
                  <label className="block text-sm font-medium text-gray-700 mb-1">
                    Açıklama
                  </label>
                  <div className="block w-full border border-gray-200 rounded-md px-3 py-2 bg-gray-50 text-gray-600 min-h-[80px]">
                    {talep.aciklama || '-'}
                  </div>
                </div>
              </div>
            </div>

            {/* Sağ Taraf - Aşama Geçmişi */}
            <div>
              <h2 className="text-lg font-medium text-gray-900 mb-6">Aşama Geçmişi</h2>
              
              {asamaGecmisi.length === 0 ? (
                <p className="text-gray-500 text-center py-8">Henüz aşama geçmişi bulunmuyor</p>
              ) : (
                <div className="space-y-4">
                  {asamaGecmisi.map((gecmis, index) => (
                    <div key={gecmis.id} className="border border-gray-200 rounded-lg p-4">
                      <div className="flex items-start justify-between">
                        <div className="flex-1">
                          <div className="flex items-center space-x-2">
                            <ClockIcon className="h-4 w-4 text-gray-400" />
                            <span className="text-sm font-medium text-gray-900">
                              {gecmis.asama?.ad}
                            </span>
                            {index === 0 && (
                              <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Güncel
                              </span>
                            )}
                          </div>
                          <p className="text-sm text-gray-600 mt-1">
                            {gecmis.aciklama}
                          </p>
                          <div className="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                            <div className="flex items-center space-x-1">
                              <UserIcon className="h-3 w-3" />
                              <span>{gecmis.degistiren_kullanici?.ad_soyad || 'Sistem'}</span>
                            </div>
                            <div className="flex items-center space-x-1">
                              <ClockIcon className="h-3 w-3" />
                              <span>{formatDate(gecmis.degistirilme_tarihi)}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </Layout>
  );
};

export default TalepDuzenle;