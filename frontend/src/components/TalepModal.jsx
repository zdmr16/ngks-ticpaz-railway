import React, { useState, useEffect } from 'react';
import { XMarkIcon } from '@heroicons/react/24/outline';
import api from '../services/api';
import { toast } from 'react-hot-toast';

const TalepModal = ({ isOpen, onClose, editingTalep = null, onSave }) => {
  const [formData, setFormData] = useState({
    bolge_id: '',
    bolge_mimari_id: '',
    bayi_id: '',
    magaza_tipi: 'kendi_magazasi',
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
    talepTurleri: []
  });

  const [loading, setLoading] = useState(false);
  const [saving, setSaving] = useState(false);

  // Modal açıldığında dropdown verilerini yükle
  useEffect(() => {
    if (isOpen) {
      loadDropdownData();
      
      // Düzenleme modunda form verilerini doldur
      if (editingTalep) {
        setFormData({
          bolge_id: editingTalep.bolge?.id || '',
          bolge_mimari_id: editingTalep.bolge_mimari?.id || '',
          bayi_id: editingTalep.bayi?.id || '',
          magaza_tipi: editingTalep.magaza_tipi || 'kendi_magazasi',
          magaza_adi: editingTalep.magaza_adi || '',
          sehir_id: editingTalep.sehir?.id || '',
          ilce_id: editingTalep.ilce?.id || '',
          talep_turu_id: editingTalep.talep_turu?.id || '',
          aciklama: editingTalep.guncel_asama_aciklamasi || ''
        });
      } else {
        // Yeni talep için form'u temizle
        setFormData({
          bolge_id: '',
          bolge_mimari_id: '',
          bayi_id: '',
          magaza_tipi: 'kendi_magazasi',
          magaza_adi: '',
          sehir_id: '',
          ilce_id: '',
          talep_turu_id: '',
          aciklama: ''
        });
      }
    }
  }, [isOpen, editingTalep]);

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
        bolge_mimari_id: ''
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
        bayi_id: ''
      }));
    }
  }, [formData.sehir_id]);

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
      const response = await api.get(`/bolge-mimarlari?bolge_id=${bolgeId}`);
      setDropdownData(prev => ({
        ...prev,
        bolgeMimarlari: response.data.data || []
      }));
    } catch (error) {
      console.error('Bölge mimarları yüklenirken hata:', error);
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

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
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
      
      if (editingTalep) {
        // Güncelleme
        await api.put(`/talepler/${editingTalep.id}`, formData);
        toast.success('Talep başarıyla güncellendi');
      } else {
        // Yeni oluşturma
        await api.post('/talepler', formData);
        toast.success('Talep başarıyla oluşturuldu');
      }
      
      onSave && onSave();
      onClose();
      
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

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto">
      <div className="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {/* Overlay */}
        <div className="fixed inset-0 transition-opacity" aria-hidden="true">
          <div className="absolute inset-0 bg-gray-500 opacity-75" onClick={onClose}></div>
        </div>

        {/* Modal */}
        <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          {/* Header */}
          <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div className="flex items-start justify-between">
              <h3 className="text-lg leading-6 font-medium text-gray-900">
                {editingTalep ? 'Talep Düzenle' : 'Yeni Talep Oluştur'}
              </h3>
              <button
                type="button"
                className="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none"
                onClick={onClose}
              >
                <XMarkIcon className="h-6 w-6" />
              </button>
            </div>

            {/* Form */}
            <form onSubmit={handleSubmit} className="mt-6 space-y-4">
              {/* Bölge */}
              <div>
                <label className="block text-sm font-medium text-gray-700">Bölge *</label>
                <select
                  name="bolge_id"
                  value={formData.bolge_id}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                  disabled={loading}
                >
                  <option value="">Bölge Seçiniz</option>
                  {dropdownData.bolgeler.map(bolge => (
                    <option key={bolge.id} value={bolge.id}>{bolge.ad}</option>
                  ))}
                </select>
              </div>

              {/* Bölge Mimarı */}
              <div>
                <label className="block text-sm font-medium text-gray-700">Bölge Mimarı *</label>
                <select
                  name="bolge_mimari_id"
                  value={formData.bolge_mimari_id}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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
                <label className="block text-sm font-medium text-gray-700">Şehir *</label>
                <select
                  name="sehir_id"
                  value={formData.sehir_id}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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
                <label className="block text-sm font-medium text-gray-700">İlçe *</label>
                <select
                  name="ilce_id"
                  value={formData.ilce_id}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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
                <label className="block text-sm font-medium text-gray-700">Bayi *</label>
                <select
                  name="bayi_id"
                  value={formData.bayi_id}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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
                <label className="block text-sm font-medium text-gray-700">Mağaza Tipi *</label>
                <select
                  name="magaza_tipi"
                  value={formData.magaza_tipi}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  required
                >
                  <option value="kendi_magazasi">Kendi Mağazası</option>
                  <option value="tali_bayi">Tali Bayi</option>
                </select>
              </div>

              {/* Mağaza Adı */}
              <div>
                <label className="block text-sm font-medium text-gray-700">Mağaza Adı *</label>
                <input
                  type="text"
                  name="magaza_adi"
                  value={formData.magaza_adi}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Mağaza adını giriniz"
                  required
                />
              </div>

              {/* Talep Türü */}
              <div>
                <label className="block text-sm font-medium text-gray-700">Talep Türü *</label>
                <select
                  name="talep_turu_id"
                  value={formData.talep_turu_id}
                  onChange={handleInputChange}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
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
                <label className="block text-sm font-medium text-gray-700">Açıklama *</label>
                <textarea
                  name="aciklama"
                  value={formData.aciklama}
                  onChange={handleInputChange}
                  rows={3}
                  className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Talep açıklamasını giriniz"
                  required
                />
              </div>

              {/* Buttons */}
              <div className="flex justify-end space-x-3 pt-4">
                <button
                  type="button"
                  onClick={onClose}
                  className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  disabled={saving}
                >
                  İptal
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                  disabled={saving || loading}
                >
                  {saving ? 'Kaydediliyor...' : (editingTalep ? 'Güncelle' : 'Oluştur')}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
};

export default TalepModal;