import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import Layout from '../components/Layout';
import api from '../services/api';
import { toast } from 'react-hot-toast';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';

const TalepOlustur = () => {
  const navigate = useNavigate();
  
  const [formData, setFormData] = useState({
    bolge_id: '',
    bolge_mimari_id: '',
    bayi_id: '',
    magaza_tipi: 'kendi_magazasi',
    magaza_id: '', // Seçilen mağaza ID'si (kendi mağazası için)
    magaza_adi: '',
    sehir_id: '',
    ilce_id: '',
    talep_turu_id: '',
    aciklama: ''
  });

  const [dropdownData, setDropdownData] = useState({
    bolgeler: [],
    bolgeMimarlari: [],
    sehirler: [],
    ilceler: [],
    bayiler: [],
    bayiMagazalari: [],
    talepTurleri: [],
    asamalar: []
  });

  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);

  // Sayfa yüklendiğinde dropdown verilerini yükle
  useEffect(() => {
    loadDropdownData();
  }, []);

  // Bölge değiştiğinde şehirleri ve bölge mimarlarını yükle
  useEffect(() => {
    if (formData.bolge_id) {
      loadSehirler(formData.bolge_id);
      loadBolgeMimarlari(formData.bolge_id);
      // Bölge değiştiğinde şehir ve ilçeyi temizle
      setFormData(prev => ({
        ...prev,
        sehir_id: '',
        ilce_id: '',
        bolge_mimari_id: '',
        bayi_id: '',
        magaza_id: '',
        magaza_adi: ''
      }));
      // Bağımlı dropdown'ları temizle
      setDropdownData(prev => ({
        ...prev,
        ilceler: [],
        bayiler: [],
        bayiMagazalari: []
      }));
    }
  }, [formData.bolge_id]);

  // Şehir değiştiğinde ilçeleri ve bayileri yükle
  useEffect(() => {
    if (formData.sehir_id) {
      loadIlceler(formData.sehir_id);
      loadBayiler(formData.sehir_id);
      // Şehir değiştiğinde ilçeyi temizle
      setFormData(prev => ({
        ...prev,
        ilce_id: '',
        bayi_id: '',
        magaza_id: '',
        magaza_adi: ''
      }));
      // Bağımlı dropdown'ları temizle
      setDropdownData(prev => ({
        ...prev,
        bayiMagazalari: []
      }));
    }
  }, [formData.sehir_id]);

  // Bayi değiştiğinde mağazaları yükle
  useEffect(() => {
    if (formData.bayi_id) {
      loadBayiMagazalari(formData.bayi_id);
      // Bayi değiştiğinde mağaza seçimini temizle
      setFormData(prev => ({
        ...prev,
        magaza_id: '',
        magaza_adi: ''
      }));
    }
  }, [formData.bayi_id]);

  // Talep türü değiştiğinde aşamaları yükle
  useEffect(() => {
    if (formData.talep_turu_id) {
      loadAsamalar(formData.talep_turu_id);
    }
  }, [formData.talep_turu_id]);

  const loadDropdownData = async () => {
    try {
      setLoading(true);
      
      const [bolgelerRes, talepTurleriRes] = await Promise.all([
        api.get('/locations/bolgeler'),
        api.get('/talep-turleri')
      ]);

      setDropdownData(prev => ({
        ...prev,
        bolgeler: bolgelerRes.data.data || [],
        talepTurleri: talepTurleriRes.data.data || []
      }));

    } catch (error) {
      console.error('Dropdown verileri yüklenirken hata:', error);
      toast.error('Form verileri yüklenemedi');
    } finally {
      setLoading(false);
    }
  };

  const loadSehirler = async (bolgeId) => {
    try {
      const response = await api.get(`/locations/sehirler?bolge_id=${bolgeId}`);
      setDropdownData(prev => ({
        ...prev,
        sehirler: response.data.data || []
      }));
    } catch (error) {
      console.error('Şehirler yüklenirken hata:', error);
      toast.error('Şehirler yüklenemedi');
    }
  };

  const loadBolgeMimarlari = async (bolgeId) => {
    try {
      console.log('🔍 BÖLGE MİMARLARI API ÇAĞRISI BAŞLADI:', bolgeId);
      // Doğru endpoint kullan: /bolge-mimarlari/bolge/{id}
      const response = await api.get(`/bolge-mimarlari/bolge/${bolgeId}`);
      console.log('✅ BÖLGE MİMARLARI API RESPONSE:', response.data);
      console.log('📊 MİMAR SAYISI:', response.data.data?.length || 0);
      setDropdownData(prev => ({
        ...prev,
        bolgeMimarlari: response.data.data || []
      }));
    } catch (error) {
      console.error('❌ BÖLGE MİMARLARI API HATASI:', error);
      toast.error('Bölge mimarları yüklenemedi');
    }
  };

  const loadIlceler = async (sehirId) => {
    try {
      const response = await api.get(`/locations/ilceler?sehir_id=${sehirId}`);
      setDropdownData(prev => ({
        ...prev,
        ilceler: response.data.data || []
      }));
    } catch (error) {
      console.error('İlçeler yüklenirken hata:', error);
      toast.error('İlçeler yüklenemedi');
    }
  };

  const loadBayiler = async (sehirId) => {
    try {
      const response = await api.get(`/bayiler?sehir_id=${sehirId}`);
      setDropdownData(prev => ({
        ...prev,
        bayiler: response.data.data || []
      }));
    } catch (error) {
      console.error('Bayiler yüklenirken hata:', error);
      toast.error('Bayiler yüklenemedi');
    }
  };

  const loadBayiMagazalari = async (bayiId) => {
    try {
      const response = await api.get(`/bayiler/${bayiId}/magazalar`);
      setDropdownData(prev => ({
        ...prev,
        bayiMagazalari: response.data.data || []
      }));
    } catch (error) {
      console.error('Bayi mağazaları yüklenirken hata:', error);
      toast.error('Mağazalar yüklenemedi');
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
    
    // Mağaza seçimi özel logic
    if (name === 'magaza_id' && formData.magaza_tipi === 'kendi_magazasi') {
      // Seçilen mağazanın adını bul
      const selectedMagaza = dropdownData.bayiMagazalari.find(m => m.id == value);
      setFormData(prev => ({
        ...prev,
        [name]: value,
        magaza_adi: selectedMagaza ? selectedMagaza.ad : ''
      }));
    } else if (name === 'magaza_tipi') {
      // Mağaza tipi değiştiğinde seçimleri temizle
      setFormData(prev => ({
        ...prev,
        [name]: value,
        magaza_id: '',
        magaza_adi: ''
      }));
    } else {
      setFormData(prev => ({
        ...prev,
        [name]: value
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Form validasyonu
    const requiredFields = [
      'bolge_id', 'bolge_mimari_id', 'bayi_id', 'magaza_adi',
      'sehir_id', 'ilce_id', 'talep_turu_id', 'aciklama'
    ];
    
    for (const field of requiredFields) {
      if (!formData[field]) {
        toast.error(`${getFieldLabel(field)} alanı zorunludur`);
        return;
      }
    }

    try {
      setSaving(true);
      
      await api.post('/talepler', formData);
      toast.success('Talep başarıyla oluşturuldu');
      navigate('/talepler');
      
    } catch (error) {
      console.error('Talep kaydetme hatası:', error);
      
      if (error.response?.data?.errors) {
        // Validation hataları
        const errors = error.response.data.errors;
        Object.keys(errors).forEach(field => {
          errors[field].forEach(message => {
            toast.error(message);
          });
        });
      } else {
        toast.error(error.response?.data?.message || 'Talep kaydedilirken hata oluştu');
      }
    } finally {
      setSaving(false);
    }
  };

  const getFieldLabel = (field) => {
    const labels = {
      bolge_id: 'Bölge',
      bolge_mimari_id: 'Bölge Mimarı',
      bayi_id: 'Bayi',
      magaza_adi: 'Mağaza Adı',
      sehir_id: 'Şehir',
      ilce_id: 'İlçe',
      talep_turu_id: 'Talep Türü',
      aciklama: 'Açıklama'
    };
    return labels[field] || field;
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
                <h1 className="text-2xl font-bold text-gray-900">Yeni Talep Oluştur</h1>
                <p className="text-gray-600 mt-1">Tüm alanları doldurup yeni talep oluşturun</p>
              </div>
            </div>
          </div>
        </div>

        {/* Form */}
        <div className="bg-white shadow rounded-lg p-6">
          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {/* Bölge */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Bölge *
                </label>
                <select
                  name="bolge_id"
                  value={formData.bolge_id}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="">Bölge Seçiniz</option>
                  {dropdownData.bolgeler.map(bolge => (
                    <option key={bolge.id} value={bolge.id}>{bolge.ad}</option>
                  ))}
                </select>
              </div>

              {/* Bölge Mimarı */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Bölge Mimarı *
                </label>
                <select
                  name="bolge_mimari_id"
                  value={formData.bolge_mimari_id}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  disabled={!formData.bolge_id}
                >
                  <option value="">Bölge Mimarı Seçiniz</option>
                  {dropdownData.bolgeMimarlari.map(mimar => (
                    <option key={mimar.id} value={mimar.id}>{mimar.ad_soyad}</option>
                  ))}
                </select>
              </div>

              {/* Şehir */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Şehir *
                </label>
                <select
                  name="sehir_id"
                  value={formData.sehir_id}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  disabled={!formData.bolge_id}
                >
                  <option value="">Şehir Seçiniz</option>
                  {dropdownData.sehirler.map(sehir => (
                    <option key={sehir.id} value={sehir.id}>{sehir.ad}</option>
                  ))}
                </select>
              </div>

              {/* İlçe */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  İlçe *
                </label>
                <select
                  name="ilce_id"
                  value={formData.ilce_id}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  disabled={!formData.sehir_id}
                >
                  <option value="">İlçe Seçiniz</option>
                  {dropdownData.ilceler.map(ilce => (
                    <option key={ilce.id} value={ilce.id}>{ilce.ad}</option>
                  ))}
                </select>
              </div>

              {/* Bayi */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Bayi *
                </label>
                <select
                  name="bayi_id"
                  value={formData.bayi_id}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  disabled={!formData.sehir_id}
                >
                  <option value="">Bayi Seçiniz</option>
                  {dropdownData.bayiler.map(bayi => (
                    <option key={bayi.id} value={bayi.id}>{bayi.ad} - {bayi.sahip_adi}</option>
                  ))}
                </select>
              </div>

              {/* Mağaza Tipi */}
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Mağaza Tipi *
                </label>
                <select
                  name="magaza_tipi"
                  value={formData.magaza_tipi}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="kendi_magazasi">Kendi Mağazası</option>
                  <option value="tali_bayi">Tali Bayi</option>
                </select>
              </div>
            </div>

            {/* Kendi Mağazası Dropdown */}
            {formData.magaza_tipi === 'kendi_magazasi' && formData.bayi_id && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Mağaza Seçiniz *
                </label>
                <select
                  name="magaza_id"
                  value={formData.magaza_id}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  disabled={!formData.bayi_id}
                >
                  <option value="">Mağaza Seçiniz</option>
                  {dropdownData.bayiMagazalari.map(magaza => (
                    <option key={magaza.id} value={magaza.id}>
                      {magaza.ad} {magaza.aciklama && `(${magaza.aciklama})`}
                    </option>
                  ))}
                </select>
              </div>
            )}

            {/* Tali Bayi Manuel Input */}
            {formData.magaza_tipi === 'tali_bayi' && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Mağaza Adı *
                </label>
                <input
                  type="text"
                  name="magaza_adi"
                  value={formData.magaza_adi}
                  onChange={handleInputChange}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Tali bayi mağaza adını giriniz"
                  required
                />
              </div>
            )}

            {/* Seçilen Mağaza Adı (Read-only for Kendi Mağazası) */}
            {formData.magaza_tipi === 'kendi_magazasi' && formData.magaza_adi && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Mağaza Adı
                </label>
                <input
                  type="text"
                  value={formData.magaza_adi}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-50 text-gray-600"
                  readOnly
                />
                <p className="mt-1 text-sm text-gray-500">
                  Seçilen mağazaya göre otomatik doldurulmuştur
                </p>
              </div>
            )}

            {/* Talep Türü */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Talep Türü *
              </label>
              <select
                name="talep_turu_id"
                value={formData.talep_turu_id}
                onChange={handleInputChange}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                required
              >
                <option value="">Talep Türü Seçiniz</option>
                {dropdownData.talepTurleri.map(tur => (
                  <option key={tur.id} value={tur.id}>{tur.ad} ({tur.is_akisi_tipi.toUpperCase()})</option>
                ))}
              </select>
            </div>

            {/* Açıklama */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Açıklama *
              </label>
              <textarea
                name="aciklama"
                value={formData.aciklama}
                onChange={handleInputChange}
                rows={4}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="Talep açıklamasını giriniz"
                required
              />
            </div>

            {/* Buttons */}
            <div className="flex justify-end space-x-3 pt-6 border-t">
              <button
                type="button"
                onClick={() => navigate('/talepler')}
                className="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                disabled={saving}
              >
                İptal
              </button>
              <button
                type="submit"
                className="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                disabled={saving}
              >
                {saving ? 'Oluşturuluyor...' : 'Talep Oluştur'}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Layout>
  );
};

export default TalepOlustur;