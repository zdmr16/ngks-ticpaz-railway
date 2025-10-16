import React, { useState, useEffect, useRef } from 'react';
import Layout from '../components/Layout';
import {
  UsersIcon,
  BuildingOfficeIcon,
  PencilIcon,
  TrashIcon,
  PlusIcon,
  XMarkIcon
} from '@heroicons/react/24/outline';
import { bolgeMimarlariService, bayilerService, locationService } from '../services/api';
import toast from 'react-hot-toast';

const Tanimlamalar = () => {
  const [activeTab, setActiveTab] = useState('bolgeMimarlari');

  const tabs = [
    {
      key: 'bolgeMimarlari',
      label: 'Bölge Mimarları',
      icon: UsersIcon,
      description: 'Bölge mimarı tanımlamaları ve atamaları'
    },
    {
      key: 'bayiler',
      label: 'Bayiler',
      icon: BuildingOfficeIcon,
      description: 'Bayi tanımlamaları ve çalışan bilgileri'
    }
  ];

  const renderTabContent = () => {
    switch (activeTab) {
      case 'bolgeMimarlari':
        return <BolgeMimarlariTab />;
      case 'bayiler':
        return <BayilerTab />;
      default:
        return <BolgeMimarlariTab />;
    }
  };

  return (
    <Layout>
      <div className="space-y-6">
        {/* Header */}
        <div className="bg-white shadow rounded-lg p-6">
          <h1 className="text-2xl font-bold text-gray-900">Tanımlamalar</h1>
          <p className="text-gray-600 mt-1">Sistem ana verilerinin yönetimi</p>
        </div>

        {/* Tabs */}
        <div className="bg-white shadow rounded-lg">
          <div className="border-b border-gray-200">
            <nav className="-mb-px flex space-x-8 px-6" aria-label="Tabs">
              {tabs.map((tab) => {
                const Icon = tab.icon;
                return (
                  <button
                    key={tab.key}
                    onClick={() => setActiveTab(tab.key)}
                    className={`${
                      activeTab === tab.key
                        ? 'border-blue-500 text-blue-600'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2`}
                  >
                    <Icon className="h-5 w-5" />
                    <span>{tab.label}</span>
                  </button>
                );
              })}
            </nav>
          </div>

          {/* Tab Content */}
          <div className="p-6">
            {renderTabContent()}
          </div>
        </div>
      </div>
    </Layout>
  );
};

// Bölge Mimarları Tab
const BolgeMimarlariTab = () => {
  const [bolgeler, setBolgeler] = useState([]);
  const [mimarlari, setMimarlari] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingItem, setEditingItem] = useState(null);
  const [formData, setFormData] = useState({
    ad_soyad: '',
    email: '',
    telefon: '',
    aktif: true,
    bolge_id: ''
  });

  // API call'ı sadece bir kez yapmak için ref kullan
  const hasLoadedData = useRef(false);

  // Component mount'ta verileri yükle
  useEffect(() => {
    if (!hasLoadedData.current) {
      hasLoadedData.current = true;
      loadData();
    }
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      const [bolgelerResponse, mimarlariResponse] = await Promise.all([
        locationService.getBolgeler(),
        bolgeMimarlariService.getAll()
      ]);
      
      console.log('Bölgeler Response:', bolgelerResponse.data);
      console.log('Mimarları Response:', mimarlariResponse.data);
      
      setBolgeler(bolgelerResponse.data.data || []);
      setMimarlari(mimarlariResponse.data.data || []);
    } catch (error) {
      console.error('Veriler yüklenirken hata:', error);
      console.error('Error response:', error.response?.data);
      toast.error('Veriler yüklenirken hata oluştu: ' + (error.response?.data?.message || error.message));
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    try {
      console.log('Form Data:', formData);
      
      let response;
      if (editingItem) {
        // Update existing
        console.log('Updating mimar with ID:', editingItem.id);
        response = await bolgeMimarlariService.update(editingItem.id, formData);
        toast.success('Bölge mimarı başarıyla güncellendi');
      } else {
        // Add new
        console.log('Creating new mimar');
        response = await bolgeMimarlariService.create(formData);
        toast.success('Bölge mimarı başarıyla eklendi');
      }
      
      console.log('Save Response:', response.data);
      
      // Verileri yeniden yükle
      await loadData();
      handleCancel();
    } catch (error) {
      console.error('Kaydetme hatası:', error);
      console.error('Error response:', error.response?.data);
      const errorMessage = error.response?.data?.message || error.message || 'Bilinmeyen hata';
      toast.error('Kaydetme sırasında hata oluştu: ' + errorMessage);
    }
  };

  const handleEdit = (item) => {
    setEditingItem(item);
    setFormData({
      ad_soyad: item.ad_soyad,
      email: item.email,
      telefon: item.telefon || '',
      aktif: item.aktif,
      bolge_id: item.bolgeler && item.bolgeler.length > 0 ? item.bolgeler[0].id : ''
    });
    setShowForm(true);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Bu bölge mimarını silmek istediğinizden emin misiniz?')) {
      try {
        await bolgeMimarlariService.delete(id);
        toast.success('Bölge mimarı başarıyla silindi');
        await loadData();
      } catch (error) {
        console.error('Silme hatası:', error);
        toast.error('Silme sırasında hata oluştu');
      }
    }
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingItem(null);
    setFormData({ ad_soyad: '', email: '', telefon: '', aktif: true, bolge_id: '' });
  };

  const isFormValid = () => {
    return formData.ad_soyad.trim() && formData.email.trim() && formData.bolge_id;
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-lg font-medium text-gray-900">Bölge Mimarları</h2>
          <p className="text-sm text-gray-600">Sisteme kayıtlı bölge mimarlarını ve bölge atamalarını yönetin</p>
        </div>
        <button
          onClick={() => setShowForm(true)}
          className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          <PlusIcon className="h-4 w-4 mr-2" />
          Yeni Mimar Ekle
        </button>
      </div>

      {/* Form */}
      {showForm && (
        <div className="bg-gray-50 rounded-lg p-4 border">
          <h3 className="text-sm font-medium text-gray-900 mb-4">
            {editingItem ? 'Mimar Düzenle' : 'Yeni Mimar Ekle'}
          </h3>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Ad Soyad *
              </label>
              <input
                type="text"
                value={formData.ad_soyad}
                onChange={(e) => setFormData({ ...formData, ad_soyad: e.target.value })}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="Mimar adı soyadı"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                E-posta *
              </label>
              <input
                type="email"
                value={formData.email}
                onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="ornek@email.com"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Telefon
              </label>
              <input
                type="text"
                value={formData.telefon}
                onChange={(e) => setFormData({ ...formData, telefon: e.target.value })}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                placeholder="0532 123 45 67"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Durum
              </label>
              <select
                value={formData.aktif}
                onChange={(e) => setFormData({ ...formData, aktif: e.target.value === 'true' })}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="true">Aktif</option>
                <option value="false">Pasif</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Sorumlu Bölge
              </label>
              <select
                value={formData.bolge_id}
                onChange={(e) => setFormData({ ...formData, bolge_id: e.target.value })}
                className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Bölge seçiniz</option>
                {bolgeler.map((bolge) => (
                  <option key={bolge.id} value={bolge.id}>
                    {bolge.ad}
                  </option>
                ))}
              </select>
              <p className="text-xs text-gray-500 mt-1">
                Bu mimarın sorumlu olacağı bölgeyi seçin
              </p>
            </div>
          </div>
          <div className="flex justify-end space-x-3 mt-4">
            <button
              onClick={handleCancel}
              className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >
              İptal
            </button>
            <button
              onClick={handleSave}
              disabled={!isFormValid()}
              className="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              {editingItem ? 'Güncelle' : 'Kaydet'}
            </button>
          </div>
        </div>
      )}

      {/* Table */}
      <div className="bg-white shadow rounded-lg overflow-hidden">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ad Soyad
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Bölge
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                İletişim
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Kayıt Tarihi
              </th>
              <th className="relative px-6 py-3">
                <span className="sr-only">İşlemler</span>
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {loading ? (
              <tr>
                <td colSpan="5" className="px-6 py-4 text-center text-sm text-gray-500">
                  Yükleniyor...
                </td>
              </tr>
            ) : (
              mimarlari.map((mimar) => (
                <tr key={mimar.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {mimar.ad_soyad}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {mimar.bolgeler && mimar.bolgeler.length > 0 ? (
                      <span className="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                        {mimar.bolgeler[0].ad}
                      </span>
                    ) : (
                      <span className="text-gray-400 text-xs">Bölge atanmamış</span>
                    )}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div>{mimar.telefon || '-'}</div>
                    <div>{mimar.email}</div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div className="flex flex-col">
                      <span>{new Date(mimar.created_at).toLocaleDateString('tr-TR')}</span>
                      <span className={`text-xs font-semibold ${mimar.aktif ? 'text-green-600' : 'text-red-600'}`}>
                        {mimar.aktif ? 'Aktif' : 'Pasif'}
                      </span>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div className="flex justify-end space-x-2">
                      <button
                        onClick={() => handleEdit(mimar)}
                        className="text-blue-600 hover:text-blue-900 p-1"
                        title="Düzenle"
                      >
                        <PencilIcon className="h-4 w-4" />
                      </button>
                      <button
                        onClick={() => handleDelete(mimar.id)}
                        className="text-red-600 hover:text-red-900 p-1"
                        title="Sil"
                      >
                        <TrashIcon className="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
        {mimarlari.length === 0 && (
          <div className="text-center py-12">
            <UsersIcon className="mx-auto h-12 w-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-medium text-gray-900">Mimar bulunamadı</h3>
            <p className="mt-1 text-sm text-gray-500">İlk mimarınızı ekleyerek başlayın.</p>
          </div>
        )}
      </div>
    </div>
  );
};

// Bayiler Tab
const BayilerTab = () => {
  const [bayiler, setBayiler] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingItem, setEditingItem] = useState(null);
  const [formData, setFormData] = useState({
    ad: '',
    sahip_adi: '',
    sahip_telefon: '',
    sahip_email: '',
    sehir_id: '',
    ilce_id: '',
    calisanlar: [{ ad: '', soyad: '', telefon: '', email: '' }]
  });

  // Mağaza yönetimi state'leri
  const [selectedBayi, setSelectedBayi] = useState(null);
  const [showMagazaPanel, setShowMagazaPanel] = useState(false);
  const [magazalar, setMagazalar] = useState([]);
  const [magazaLoading, setMagazaLoading] = useState(false);
  const [showMagazaForm, setShowMagazaForm] = useState(false);
  const [editingMagaza, setEditingMagaza] = useState(null);
  const [magazaFormData, setMagazaFormData] = useState({
    ad: '',
    aciklama: '',
    aktif: true
  });

  // Dropdown verileri
  const [dropdownData, setDropdownData] = useState({
    sehirler: [],
    ilceler: []
  });

  // API call'ı sadece bir kez yapmak için ref kullan
  const hasLoadedData = useRef(false);

  // Component mount'ta verileri yükle
  useEffect(() => {
    if (!hasLoadedData.current) {
      hasLoadedData.current = true;
      loadData();
    }
  }, []);

  // FormData değişikliklerini izle (debug için)
  useEffect(() => {
    console.log('=== FORM DATA UPDATED ===');
    console.log('Current formData:', formData);
  }, [formData]);

  const loadData = async () => {
    try {
      setLoading(true);
      const [bayilerResponse, sehirlerResponse] = await Promise.all([
        bayilerService.getAll(),
        locationService.getSehirler()
      ]);
      
      console.log('Bayiler Response:', bayilerResponse.data);
      console.log('Şehirler Response:', sehirlerResponse.data);
      
      setBayiler(bayilerResponse.data.data || []);
      setDropdownData(prev => ({ ...prev, sehirler: sehirlerResponse.data.data || [] }));
    } catch (error) {
      console.error('Veriler yüklenirken hata:', error);
      console.error('Error response:', error.response?.data);
      toast.error('Veriler yüklenirken hata oluştu: ' + (error.response?.data?.message || error.message));
    } finally {
      setLoading(false);
    }
  };

  // Şehir değiştiğinde ilçeleri yükle
  const handleSehirChange = async (sehir_id) => {
    setFormData(prevFormData => ({ ...prevFormData, sehir_id, ilce_id: '' }));
    
    if (sehir_id) {
      try {
        const response = await locationService.getSehirIlceleri(sehir_id);
        console.log('İlçeler Response:', response.data);
        
        // API response formatını kontrol et ve düzelt
        console.log('Raw İlçeler Data:', response.data.data);
        
        let ilcelerData = [];
        if (response.data.data && response.data.data.ilceler && Array.isArray(response.data.data.ilceler)) {
          // Backend'den gelen format: { sehir: "X", bolge: "Y", ilceler: [...] }
          ilcelerData = response.data.data.ilceler;
        } else if (response.data.data && Array.isArray(response.data.data)) {
          // Array ise direkt kullan
          ilcelerData = response.data.data;
        } else if (Array.isArray(response.data)) {
          // Fallback: response.data
          ilcelerData = response.data;
        } else {
          console.warn('Beklenmeyen API response formatı:', response.data);
          ilcelerData = [];
        }
        
        console.log('Processed İlçeler Data:', ilcelerData);
        setDropdownData(prev => ({ ...prev, ilceler: ilcelerData }));
      } catch (error) {
        console.error('İlçeler yüklenirken hata:', error);
        toast.error('İlçeler yüklenirken hata oluştu');
        // Hata durumunda ilçeleri temizle
        setDropdownData(prev => ({ ...prev, ilceler: [] }));
      }
    } else {
      setDropdownData(prev => ({ ...prev, ilceler: [] }));
    }
  };

  const addCalisan = () => {
    setFormData({
      ...formData,
      calisanlar: [...formData.calisanlar, { ad: '', soyad: '', telefon: '', email: '' }]
    });
  };

  const removeCalisan = (index) => {
    if (formData.calisanlar.length > 1) {
      const newCalisanlar = formData.calisanlar.filter((_, i) => i !== index);
      setFormData({ ...formData, calisanlar: newCalisanlar });
    }
  };

  const updateCalisan = (index, field, value) => {
    const newCalisanlar = [...formData.calisanlar];
    newCalisanlar[index] = { ...newCalisanlar[index], [field]: value };
    setFormData({ ...formData, calisanlar: newCalisanlar });
  };

  const handleSave = async () => {
    try {
      console.log('Form Data:', formData);
      
      let response;
      if (editingItem) {
        // Update existing
        console.log('Updating bayi with ID:', editingItem.id);
        response = await bayilerService.update(editingItem.id, formData);
        toast.success('Bayi başarıyla güncellendi');
      } else {
        // Add new
        console.log('Creating new bayi');
        response = await bayilerService.create(formData);
        toast.success('Bayi başarıyla eklendi');
      }
      
      console.log('Save Response:', response.data);
      
      // Verileri yeniden yükle
      await loadData();
      handleCancel();
    } catch (error) {
      console.error('Kaydetme hatası:', error);
      console.error('Error response:', error.response?.data);
      console.error('Validation errors:', error.response?.data?.errors);
      
      // Validation hatalarını detaylı yazdır
      if (error.response?.data?.errors) {
        Object.keys(error.response.data.errors).forEach(field => {
          console.error(`Field ${field}:`, error.response.data.errors[field]);
        });
      }
      
      const errorMessage = error.response?.data?.message || error.message || 'Bilinmeyen hata';
      toast.error('Kaydetme sırasında hata oluştu: ' + errorMessage);
    }
  };

  const handleEdit = async (item) => {
    console.log('=== BAYI EDİT DEBUG ===');
    console.log('Edit edilecek bayi:', item);
    
    setEditingItem(item);
    
    // Çalışanları ad_soyad'dan ad ve soyad'a dönüştür
    const calisanlarFormatted = item.calisanlar && item.calisanlar.length > 0
      ? item.calisanlar.map(calisan => {
          console.log('Çalışan işleniyor:', calisan);
          const adSoyad = calisan.ad_soyad || '';
          const adSoyadParts = adSoyad.trim().split(' ');
          const ad = adSoyadParts[0] || '';
          const soyad = adSoyadParts.slice(1).join(' ') || '';
          
          const formattedCalisan = {
            ad: ad,
            soyad: soyad,
            telefon: calisan.telefon || '',
            email: calisan.email || ''
          };
          console.log('Formatted çalışan:', formattedCalisan);
          
          return formattedCalisan;
        })
      : [{ ad: '', soyad: '', telefon: '', email: '' }];
    
    // İl seçiliyse önce ilçeleri yükle
    if (item.sehir_id) {
      console.log('İl seçili, ilçeler yükleniyor:', item.sehir_id);
      try {
        const response = await locationService.getSehirIlceleri(item.sehir_id);
        console.log('İlçeler Response (Edit):', response.data);
        
        let ilcelerData = [];
        if (response.data.data && response.data.data.ilceler && Array.isArray(response.data.data.ilceler)) {
          ilcelerData = response.data.data.ilceler;
        } else if (response.data.data && Array.isArray(response.data.data)) {
          ilcelerData = response.data.data;
        } else if (Array.isArray(response.data)) {
          ilcelerData = response.data;
        }
        
        console.log('Processed İlçeler Data (Edit):', ilcelerData);
        setDropdownData(prev => ({ ...prev, ilceler: ilcelerData }));
      } catch (error) {
        console.error('İlçeler yüklenirken hata (Edit):', error);
        setDropdownData(prev => ({ ...prev, ilceler: [] }));
      }
    }
    
    const formDataToSet = {
      ad: item.ad || '',
      sahip_adi: item.sahip_adi || '',
      sahip_telefon: item.sahip_telefon || '',
      sahip_email: item.sahip_email || '',
      sehir_id: item.sehir_id?.toString() || '',
      ilce_id: item.ilce_id?.toString() || '',
      calisanlar: calisanlarFormatted
    };
    
    console.log('Set edilecek form data:', formDataToSet);
    
    setFormData(formDataToSet);
    setShowForm(true);
    console.log('=== BAYI EDİT DEBUG SON ===');
  };

  const handleDelete = async (id) => {
    if (window.confirm('Bu bayiyi silmek istediğinizden emin misiniz?')) {
      try {
        await bayilerService.delete(id);
        toast.success('Bayi başarıyla silindi');
        await loadData();
      } catch (error) {
        console.error('Silme hatası:', error);
        toast.error('Silme sırasında hata oluştu');
      }
    }
  };

  const handleCancel = () => {
    setShowForm(false);
    setEditingItem(null);
    setFormData({
      ad: '',
      sahip_adi: '',
      sahip_telefon: '',
      sahip_email: '',
      sehir_id: '',
      ilce_id: '',
      calisanlar: [{ ad: '', soyad: '', telefon: '', email: '' }]
    });
    setDropdownData(prev => ({ ...prev, ilceler: [] }));
  };

  const isFormValid = () => {
    return formData.ad.trim() && formData.sehir_id && formData.ilce_id;
  };

  // Mağaza yönetimi fonksiyonları
  const loadMagazalar = async (bayiId) => {
    try {
      setMagazaLoading(true);
      const response = await bayilerService.getMagazalar(bayiId);
      console.log('Mağazalar Response:', response.data);
      setMagazalar(response.data.data || []);
    } catch (error) {
      console.error('Mağazalar yüklenirken hata:', error);
      toast.error('Mağazalar yüklenirken hata oluştu');
      setMagazalar([]);
    } finally {
      setMagazaLoading(false);
    }
  };

  const handleBayiMagazaSelect = async (bayi) => {
    setSelectedBayi(bayi);
    setShowMagazaPanel(true);
    await loadMagazalar(bayi.id);
  };

  const handleMagazaSave = async () => {
    try {
      console.log('Mağaza Form Data:', magazaFormData);
      
      let response;
      if (editingMagaza) {
        // Update existing
        response = await bayilerService.updateMagaza(selectedBayi.id, editingMagaza.id, magazaFormData);
        toast.success('Mağaza başarıyla güncellendi');
      } else {
        // Add new
        response = await bayilerService.addMagaza(selectedBayi.id, magazaFormData);
        toast.success('Mağaza başarıyla eklendi');
      }
      
      console.log('Mağaza Save Response:', response.data);
      
      // Mağazaları yeniden yükle
      await loadMagazalar(selectedBayi.id);
      handleMagazaCancel();
    } catch (error) {
      console.error('Mağaza kaydetme hatası:', error);
      const errorMessage = error.response?.data?.message || error.message || 'Bilinmeyen hata';
      toast.error('Mağaza kaydetme sırasında hata oluştu: ' + errorMessage);
    }
  };

  const handleMagazaEdit = (magaza) => {
    setEditingMagaza(magaza);
    setMagazaFormData({
      ad: magaza.ad,
      aciklama: magaza.aciklama || '',
      aktif: magaza.aktif
    });
    setShowMagazaForm(true);
  };

  const handleMagazaDelete = async (magazaId) => {
    if (window.confirm('Bu mağazayı silmek istediğinizden emin misiniz?')) {
      try {
        await bayilerService.deleteMagaza(selectedBayi.id, magazaId);
        toast.success('Mağaza başarıyla silindi');
        await loadMagazalar(selectedBayi.id);
      } catch (error) {
        console.error('Mağaza silme hatası:', error);
        toast.error('Mağaza silme sırasında hata oluştu');
      }
    }
  };

  const handleMagazaCancel = () => {
    setShowMagazaForm(false);
    setEditingMagaza(null);
    setMagazaFormData({ ad: '', aciklama: '', aktif: true });
  };

  const closeMagazaPanel = () => {
    setShowMagazaPanel(false);
    setSelectedBayi(null);
    setMagazalar([]);
    handleMagazaCancel();
  };

  const isMagazaFormValid = () => {
    return magazaFormData.ad.trim();
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-lg font-medium text-gray-900">Bayiler</h2>
          <p className="text-sm text-gray-600">Sisteme kayıtlı bayileri ve çalışanlarını yönetin</p>
        </div>
        <button
          onClick={() => setShowForm(true)}
          className="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          <PlusIcon className="h-4 w-4 mr-2" />
          Yeni Bayi Ekle
        </button>
      </div>

      {/* Form */}
      {showForm && (
        <div className="bg-gray-50 rounded-lg p-6 border">
          <h3 className="text-lg font-medium text-gray-900 mb-6">
            {editingItem ? 'Bayi Düzenle' : 'Yeni Bayi Ekle'}
          </h3>
          
          {/* Bayi Bilgileri */}
          <div className="mb-6">
            <h4 className="text-sm font-medium text-gray-900 mb-4">Bayi Bilgileri</h4>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  İşletme Adı *
                </label>
                <input
                  type="text"
                  value={formData.ad}
                  onChange={(e) => setFormData({ ...formData, ad: e.target.value })}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Bayi işletme adı"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Sahip Adı
                </label>
                <input
                  type="text"
                  value={formData.sahip_adi}
                  onChange={(e) => setFormData({ ...formData, sahip_adi: e.target.value })}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Bayi sahibi adı soyadı"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Telefon
                </label>
                <input
                  type="text"
                  value={formData.sahip_telefon}
                  onChange={(e) => setFormData({ ...formData, sahip_telefon: e.target.value })}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="0532 123 45 67"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  E-posta
                </label>
                <input
                  type="email"
                  value={formData.sahip_email}
                  onChange={(e) => setFormData({ ...formData, sahip_email: e.target.value })}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="ornek@email.com"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  İl *
                </label>
                <select
                  value={formData.sehir_id}
                  onChange={(e) => handleSehirChange(e.target.value)}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="">İl seçiniz</option>
                  {dropdownData.sehirler.map(sehir => (
                    <option key={sehir.id} value={sehir.id}>{sehir.ad}</option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  İlçe *
                </label>
                <select
                  value={formData.ilce_id}
                  onChange={(e) => setFormData({ ...formData, ilce_id: e.target.value })}
                  className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  disabled={!formData.sehir_id}
                >
                  <option value="">İlçe seçiniz</option>
                  {Array.isArray(dropdownData.ilceler) && dropdownData.ilceler.map((ilce, index) => (
                    <option key={ilce.id || index} value={ilce.id || index}>
                      {ilce.ad || ilce.name || ilce}
                    </option>
                  ))}
                </select>
              </div>
            </div>
          </div>

          {/* Çalışanlar */}
          <div className="mb-6">
            <div className="flex items-center justify-between mb-4">
              <h4 className="text-sm font-medium text-gray-900">Bayi Çalışanları</h4>
              <button
                type="button"
                onClick={addCalisan}
                className="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200"
              >
                <PlusIcon className="h-3 w-3 mr-1" />
                Çalışan Ekle
              </button>
            </div>
            
            <div className="space-y-4">
              {formData.calisanlar.map((calisan, index) => (
                <div key={index} className="bg-white p-4 rounded-lg border border-gray-200">
                  <div className="flex items-center justify-between mb-3">
                    <span className="text-sm font-medium text-gray-700">Çalışan #{index + 1}</span>
                    {formData.calisanlar.length > 1 && (
                      <button
                        type="button"
                        onClick={() => removeCalisan(index)}
                        className="text-red-600 hover:text-red-800"
                      >
                        <XMarkIcon className="h-4 w-4" />
                      </button>
                    )}
                  </div>
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">Ad</label>
                      <input
                        type="text"
                        value={calisan.ad}
                        onChange={(e) => updateCalisan(index, 'ad', e.target.value)}
                        className="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm"
                        placeholder="Çalışan adı"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">Soyad</label>
                      <input
                        type="text"
                        value={calisan.soyad}
                        onChange={(e) => updateCalisan(index, 'soyad', e.target.value)}
                        className="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm"
                        placeholder="Çalışan soyadı"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">Telefon</label>
                      <input
                        type="text"
                        value={calisan.telefon}
                        onChange={(e) => updateCalisan(index, 'telefon', e.target.value)}
                        className="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm"
                        placeholder="0532 123 45 67"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">E-posta</label>
                      <input
                        type="email"
                        value={calisan.email}
                        onChange={(e) => updateCalisan(index, 'email', e.target.value)}
                        className="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-sm"
                        placeholder="ornek@email.com"
                      />
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Mağazalar (Sadece düzenleme modunda göster) */}
          {editingItem && (
            <div className="mb-6">
              <div className="flex items-center justify-between mb-4">
                <h4 className="text-sm font-medium text-gray-900">Bayi Mağazaları</h4>
                <button
                  type="button"
                  onClick={() => handleBayiMagazaSelect(editingItem)}
                  className="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200"
                >
                  <BuildingOfficeIcon className="h-3 w-3 mr-1" />
                  Mağaza Yönetimi
                </button>
              </div>
              <div className="bg-gray-50 rounded-lg p-4">
                <p className="text-sm text-gray-600 mb-3">
                  Bu bayi için mağaza eklemek, düzenlemek veya silmek istiyorsanız yukarıdaki "Mağaza Yönetimi" butonunu kullanın.
                </p>
                <p className="text-xs text-gray-500">
                  💡 Mağaza yönetimi, bayi kaydedildikten sonra ayrı bir panel açılarak yapılır.
                </p>
              </div>
            </div>
          )}
          
          <div className="flex justify-end space-x-3">
            <button
              onClick={handleCancel}
              className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >
              İptal
            </button>
            <button
              onClick={handleSave}
              disabled={!isFormValid()}
              className="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50"
            >
              {editingItem ? 'Güncelle' : 'Kaydet'}
            </button>
          </div>
        </div>
      )}

      {/* Mağaza Yönetim Modal Paneli */}
      {showMagazaPanel && selectedBayi && (
        <div className="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
          <div className="relative mx-4 p-6 border w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl rounded-lg bg-white">
            <div className="mb-4 flex items-center justify-between">
              <div>
                <h3 className="text-lg font-medium text-gray-900">
                  {selectedBayi.ad} - Mağaza Yönetimi
                </h3>
                <p className="text-sm text-gray-600">
                  Bu bayiye ait mağazaları yönetin
                </p>
              </div>
              <button
                onClick={closeMagazaPanel}
                className="text-gray-400 hover:text-gray-600"
              >
                <XMarkIcon className="h-6 w-6" />
              </button>
            </div>

            {/* Mağaza Formu */}
            {showMagazaForm && (
              <div className="mb-6 bg-gray-50 rounded-lg p-4 border">
                <h4 className="text-sm font-medium text-gray-900 mb-4">
                  {editingMagaza ? 'Mağaza Düzenle' : 'Yeni Mağaza Ekle'}
                </h4>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                      Mağaza Adı *
                    </label>
                    <input
                      type="text"
                      value={magazaFormData.ad}
                      onChange={(e) => setMagazaFormData({ ...magazaFormData, ad: e.target.value })}
                      className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Mağaza adı"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                      Durum
                    </label>
                    <select
                      value={magazaFormData.aktif}
                      onChange={(e) => setMagazaFormData({ ...magazaFormData, aktif: e.target.value === 'true' })}
                      className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                      <option value="true">Aktif</option>
                      <option value="false">Pasif</option>
                    </select>
                  </div>
                  <div className="md:col-span-2">
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                      Açıklama
                    </label>
                    <textarea
                      rows={3}
                      value={magazaFormData.aciklama}
                      onChange={(e) => setMagazaFormData({ ...magazaFormData, aciklama: e.target.value })}
                      className="block w-full border border-gray-300 rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Mağaza hakkında açıklama"
                    />
                  </div>
                </div>
                <div className="flex justify-end space-x-3 mt-4">
                  <button
                    onClick={handleMagazaCancel}
                    className="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                  >
                    İptal
                  </button>
                  <button
                    onClick={handleMagazaSave}
                    disabled={!isMagazaFormValid()}
                    className="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 disabled:opacity-50"
                  >
                    {editingMagaza ? 'Güncelle' : 'Kaydet'}
                  </button>
                </div>
              </div>
            )}

            {/* Mağaza Listesi */}
            <div className="mb-4 flex items-center justify-between">
              <h4 className="text-sm font-medium text-gray-900">Mağaza Listesi</h4>
              <button
                onClick={() => setShowMagazaForm(true)}
                className="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200"
              >
                <PlusIcon className="h-3 w-3 mr-1" />
                Yeni Mağaza
              </button>
            </div>

            <div className="bg-white border rounded-lg overflow-hidden">
              {magazaLoading ? (
                <div className="p-6 text-center text-sm text-gray-500">
                  Mağazalar yükleniyor...
                </div>
              ) : magazalar.length === 0 ? (
                <div className="p-6 text-center">
                  <BuildingOfficeIcon className="mx-auto h-8 w-8 text-gray-400" />
                  <h3 className="mt-2 text-sm font-medium text-gray-900">Mağaza bulunamadı</h3>
                  <p className="mt-1 text-sm text-gray-500">Bu bayi için henüz mağaza eklenmemiş.</p>
                </div>
              ) : (
                <table className="min-w-full divide-y divide-gray-200">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mağaza Adı
                      </th>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Açıklama
                      </th>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Durum
                      </th>
                      <th className="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kayıt Tarihi
                      </th>
                      <th className="relative px-4 py-3">
                        <span className="sr-only">İşlemler</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody className="bg-white divide-y divide-gray-200">
                    {magazalar.map((magaza) => (
                      <tr key={magaza.id} className="hover:bg-gray-50">
                        <td className="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {magaza.ad}
                        </td>
                        <td className="px-4 py-4 text-sm text-gray-500">
                          {magaza.aciklama || '-'}
                        </td>
                        <td className="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                          <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                            magaza.aktif
                              ? 'bg-green-100 text-green-800'
                              : 'bg-red-100 text-red-800'
                          }`}>
                            {magaza.aktif ? 'Aktif' : 'Pasif'}
                          </span>
                        </td>
                        <td className="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                          {new Date(magaza.created_at).toLocaleDateString('tr-TR')}
                        </td>
                        <td className="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                          <div className="flex justify-end space-x-2">
                            <button
                              onClick={() => handleMagazaEdit(magaza)}
                              className="text-blue-600 hover:text-blue-900 p-1"
                              title="Düzenle"
                            >
                              <PencilIcon className="h-4 w-4" />
                            </button>
                            <button
                              onClick={() => handleMagazaDelete(magaza.id)}
                              className="text-red-600 hover:text-red-900 p-1"
                              title="Sil"
                            >
                              <TrashIcon className="h-4 w-4" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Table */}
      <div className="bg-white shadow rounded-lg overflow-hidden">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                İşletme Adı
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Sahip Bilgileri
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Konum
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Çalışan Sayısı
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Kayıt Tarihi
              </th>
              <th className="relative px-6 py-3">
                <span className="sr-only">İşlemler</span>
              </th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {loading ? (
              <tr>
                <td colSpan="6" className="px-6 py-4 text-center text-sm text-gray-500">
                  Yükleniyor...
                </td>
              </tr>
            ) : (
              bayiler.map((bayi) => (
              <tr key={bayi.id} className="hover:bg-gray-50">
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {bayi.ad}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div className="font-medium">{bayi.sahip_adi}</div>
                  <div className="text-gray-500">{bayi.sahip_telefon}</div>
                  <div className="text-gray-500">{bayi.sahip_email}</div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <div className="font-medium text-gray-900">{bayi.sehir?.ad || bayi.sehir_adi || '-'}</div>
                  <div className="text-gray-500">{bayi.ilce?.ad || bayi.ilce_adi || '-'}</div>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <span className="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                    {bayi.calisanlar?.length || 0} çalışan
                  </span>
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {new Date(bayi.created_at).toLocaleDateString('tr-TR')}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div className="flex justify-end space-x-2">
                    <button
                      onClick={() => handleBayiMagazaSelect(bayi)}
                      className="text-green-600 hover:text-green-900 p-1"
                      title="Mağaza Yönetimi"
                    >
                      <BuildingOfficeIcon className="h-4 w-4" />
                    </button>
                    <button
                      onClick={() => handleEdit(bayi)}
                      className="text-blue-600 hover:text-blue-900 p-1"
                      title="Düzenle"
                    >
                      <PencilIcon className="h-4 w-4" />
                    </button>
                    <button
                      onClick={() => handleDelete(bayi.id)}
                      className="text-red-600 hover:text-red-900 p-1"
                      title="Sil"
                    >
                      <TrashIcon className="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              ))
            )}
          </tbody>
        </table>
        {bayiler.length === 0 && (
          <div className="text-center py-12">
            <BuildingOfficeIcon className="mx-auto h-12 w-12 text-gray-400" />
            <h3 className="mt-2 text-sm font-medium text-gray-900">Bayi bulunamadı</h3>
            <p className="mt-1 text-sm text-gray-500">İlk bayinizi ekleyerek başlayın.</p>
          </div>
        )}
      </div>
    </div>
  );
};

export default Tanimlamalar;