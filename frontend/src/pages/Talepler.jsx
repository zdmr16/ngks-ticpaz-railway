import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import Layout from '../components/Layout';
import api from '../services/api';
import { toast } from 'react-hot-toast';
import {
  PlusIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
  MagnifyingGlassIcon,
  FunnelIcon,
  ArchiveBoxIcon,
  ChevronLeftIcon,
  ChevronRightIcon
} from '@heroicons/react/24/outline';

const Talepler = () => {
  const navigate = useNavigate();
  const [talepler, setTalepler] = useState([]);
  const [pagination, setPagination] = useState({});
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [showArchived, setShowArchived] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [asamalar, setAsamalar] = useState([]);
  const [showFilters, setShowFilters] = useState(false);
  
  // Gelişmiş filtreleme state'leri
  const [filters, setFilters] = useState({
    bolge: '',
    bolgeMimari: '',
    bayi: '',
    sehir: '',
    magazaTipi: '',
    talepTuru: '',
    asama: '',
    tarihFiltresi: ''
  });
  
  // Dinamik filtre seçenekleri
  const [filterOptions, setFilterOptions] = useState({
    bolgeler: [],
    bolgeMimarlari: [],
    bayiler: [],
    sehirler: [],
    talepTurleri: [],
    asamalar: []
  });

  useEffect(() => {
    fetchTalepler();
    fetchAsamalar();
  }, [currentPage, showArchived]);

  const fetchTalepler = async () => {
    try {
      setLoading(true);
      const params = {
        page: currentPage,
        per_page: 25
      };
      
      if (showArchived) {
        params.arsiv = true;
      }
      
      const response = await api.get('/talepler', { params });
      setTalepler(response.data.data || []);
      setPagination(response.data.pagination || {});
    } catch (error) {
      console.error('Talepler yüklenirken hata:', error);
      toast.error('Talepler yüklenemedi');
      setTalepler([]);
      setPagination({});
    } finally {
      setLoading(false);
    }
  };

  const fetchAsamalar = async () => {
    try {
      const response = await api.get('/asamalar');
      setAsamalar(response.data.data || []);
    } catch (error) {
      console.error('Aşamalar yüklenirken hata:', error);
    }
  };

  // Dinamik filtre seçeneklerini güncelle
  useEffect(() => {
    if (talepler.length > 0) {
      const uniqueBolgeler = [...new Set(talepler.map(t => t.bolge?.ad).filter(Boolean))];
      const uniqueBolgeMimarlari = [...new Set(talepler.map(t => t.bolge_mimari?.ad_soyad).filter(Boolean))];
      const uniqueBayiler = [...new Set(talepler.map(t => t.bayi?.ad).filter(Boolean))];
      const uniqueSehirler = [...new Set(talepler.map(t => t.sehir?.ad).filter(Boolean))];
      const uniqueTalepTurleri = [...new Set(talepler.map(t => t.talep_turu?.ad).filter(Boolean))];
      const uniqueAsamalar = [...new Set(talepler.map(t => t.guncel_asama?.ad).filter(Boolean))];
      
      setFilterOptions({
        bolgeler: uniqueBolgeler.sort(),
        bolgeMimarlari: uniqueBolgeMimarlari.sort(),
        bayiler: uniqueBayiler.sort(),
        sehirler: uniqueSehirler.sort(),
        talepTurleri: uniqueTalepTurleri.sort(),
        asamalar: uniqueAsamalar.sort()
      });
    }
  }, [talepler]);

  // Gelişmiş filtreleme logic'i - AND mantığı
  const filteredTalepler = talepler.filter(talep => {
    // Genel arama
    const searchText = searchTerm.toLowerCase();
    const matchesSearch = !searchTerm ||
      talep.bolge?.ad?.toLowerCase().includes(searchText) ||
      talep.bolge_mimari?.ad_soyad?.toLowerCase().includes(searchText) ||
      talep.bayi?.ad?.toLowerCase().includes(searchText) ||
      talep.magaza_adi?.toLowerCase().includes(searchText) ||
      talep.sehir?.ad?.toLowerCase().includes(searchText) ||
      talep.ilce?.ad?.toLowerCase().includes(searchText) ||
      talep.talep_turu?.ad?.toLowerCase().includes(searchText) ||
      talep.guncel_asama?.ad?.toLowerCase().includes(searchText);

    // Spesifik filtreler (AND mantığı)
    const matchesBolge = !filters.bolge || talep.bolge?.ad === filters.bolge;
    const matchesBolgeMimari = !filters.bolgeMimari || talep.bolge_mimari?.ad_soyad === filters.bolgeMimari;
    const matchesBayi = !filters.bayi || talep.bayi?.ad === filters.bayi;
    const matchesSehir = !filters.sehir || talep.sehir?.ad === filters.sehir;
    const matchesMagazaTipi = !filters.magazaTipi || talep.magaza_tipi === filters.magazaTipi;
    const matchesTalepTuru = !filters.talepTuru || talep.talep_turu?.ad === filters.talepTuru;
    const matchesAsama = !filters.asama || talep.guncel_asama?.ad === filters.asama;
    
    // Tarih filtresi
    let matchesTarih = true;
    if (filters.tarihFiltresi && talep.guncel_asama_tarihi) {
      const talepTarihi = new Date(talep.guncel_asama_tarihi);
      const bugun = new Date();
      const farkGun = Math.floor((bugun - talepTarihi) / (1000 * 60 * 60 * 24));
      
      switch (filters.tarihFiltresi) {
        case '3gun':
          matchesTarih = farkGun <= 3;
          break;
        case '7gun':
          matchesTarih = farkGun <= 7;
          break;
        case '14gun':
          matchesTarih = farkGun <= 14;
          break;
        case '30gun':
          matchesTarih = farkGun <= 30;
          break;
        default:
          matchesTarih = true;
      }
    }

    return matchesSearch && matchesBolge && matchesBolgeMimari && matchesBayi &&
           matchesSehir && matchesMagazaTipi && matchesTalepTuru && matchesAsama && matchesTarih;
  });

  const handlePageChange = (page) => {
    setCurrentPage(page);
  };

  const handleArchiveToggle = () => {
    setShowArchived(!showArchived);
    setCurrentPage(1);
  };

  const handleFilterChange = (filterName, value) => {
    setFilters(prev => ({
      ...prev,
      [filterName]: value
    }));
    setCurrentPage(1);
  };

  const handleColumnHeaderClick = (columnType, value) => {
    setFilters(prev => ({
      ...prev,
      [columnType]: prev[columnType] === value ? '' : value
    }));
    setCurrentPage(1);
  };

  const clearAllFilters = () => {
    setSearchTerm('');
    setFilters({
      bolge: '',
      bolgeMimari: '',
      bayi: '',
      sehir: '',
      magazaTipi: '',
      talepTuru: '',
      asama: '',
      tarihFiltresi: ''
    });
    setCurrentPage(1);
  };

  const handleNewTalep = () => {
    navigate('/talepler/yeni');
  };

  const handleViewTalep = (id) => {
    navigate(`/talepler/${id}/duzenle`);
  };

  const handleEditTalep = (talep) => {
    navigate(`/talepler/${talep.id}/duzenle`);
  };

  const handleDeleteTalep = async (id) => {
    if (!window.confirm('Bu talebi silmek istediğinizden emin misiniz?')) {
      return;
    }

    try {
      await api.delete(`/talepler/${id}`);
      toast.success('Talep başarıyla silindi');
      fetchTalepler(); // Listeyi yenile
    } catch (error) {
      console.error('Talep silme hatası:', error);
      toast.error(error.response?.data?.message || 'Talep silinirken hata oluştu');
    }
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
        <div className="sm:flex sm:items-center">
          <div className="sm:flex-auto">
            <h1 className="text-2xl font-semibold leading-6 text-gray-900">Talepler</h1>
            <p className="mt-2 text-sm text-gray-700">
              Tüm taleplerin listesi ve yönetimi - Proje dokümanına uygun veri yapısı
            </p>
          </div>
          <div className="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <button
              type="button"
              onClick={handleNewTalep}
              className="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
            >
              <PlusIcon className="h-5 w-5 inline mr-2" />
              Yeni Talep
            </button>
          </div>
        </div>

        {/* Filtre Toggle Butonu (Mobil) */}
        <div className="md:hidden mb-4">
          <button
            onClick={() => setShowFilters(!showFilters)}
            className="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <FunnelIcon className="h-5 w-5 mr-2" />
            {showFilters ? 'Filtreleri Gizle' : 'Filtreleri Göster'}
          </button>
        </div>

        {/* Gelişmiş Filters */}
        <div className={`bg-white shadow rounded-lg p-6 ${!showFilters ? 'hidden md:block' : ''}`}>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
            {/* Genel Arama */}
            <div>
              <label htmlFor="search" className="block text-sm font-medium text-gray-700 mb-2">
                Genel Arama
              </label>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <MagnifyingGlassIcon className="h-5 w-5 text-gray-400" />
                </div>
                <input
                  type="text"
                  id="search"
                  className="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Tüm alanlarda ara..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                />
              </div>
            </div>

            {/* Bölge Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Bölge
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.bolge}
                onChange={(e) => handleFilterChange('bolge', e.target.value)}
              >
                <option value="">Tüm Bölgeler</option>
                {filterOptions.bolgeler.map(bolge => (
                  <option key={bolge} value={bolge}>{bolge}</option>
                ))}
              </select>
            </div>

            {/* Bölge Mimarı Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Bölge Mimarı
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.bolgeMimari}
                onChange={(e) => handleFilterChange('bolgeMimari', e.target.value)}
              >
                <option value="">Tüm Mimarlar</option>
                {filterOptions.bolgeMimarlari.map(mimar => (
                  <option key={mimar} value={mimar}>{mimar}</option>
                ))}
              </select>
            </div>

            {/* Bayi Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Bayi
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.bayi}
                onChange={(e) => handleFilterChange('bayi', e.target.value)}
              >
                <option value="">Tüm Bayiler</option>
                {filterOptions.bayiler.map(bayi => (
                  <option key={bayi} value={bayi}>{bayi}</option>
                ))}
              </select>
            </div>

            {/* Şehir Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Şehir
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.sehir}
                onChange={(e) => handleFilterChange('sehir', e.target.value)}
              >
                <option value="">Tüm Şehirler</option>
                {filterOptions.sehirler.map(sehir => (
                  <option key={sehir} value={sehir}>{sehir}</option>
                ))}
              </select>
            </div>

            {/* Mağaza Tipi Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Mağaza Tipi
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.magazaTipi}
                onChange={(e) => handleFilterChange('magazaTipi', e.target.value)}
              >
                <option value="">Tüm Mağaza Tipleri</option>
                <option value="kendi_magazasi">Kendi Mağazası</option>
                <option value="tali_bayi">Tali Bayi</option>
              </select>
            </div>

            {/* Talep Türü Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Talep Türü
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.talepTuru}
                onChange={(e) => handleFilterChange('talepTuru', e.target.value)}
              >
                <option value="">Tüm Talep Türleri</option>
                {filterOptions.talepTurleri.map(tur => (
                  <option key={tur} value={tur}>{tur}</option>
                ))}
              </select>
            </div>

            {/* Aşama Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Aşama
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.asama}
                onChange={(e) => handleFilterChange('asama', e.target.value)}
              >
                <option value="">Tüm Aşamalar</option>
                {filterOptions.asamalar.map(asama => (
                  <option key={asama} value={asama}>{asama}</option>
                ))}
              </select>
            </div>

            {/* Tarih Filtresi */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Aşama Tarihi
              </label>
              <select
                className="block w-full px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                value={filters.tarihFiltresi}
                onChange={(e) => handleFilterChange('tarihFiltresi', e.target.value)}
              >
                <option value="">Tüm Tarihler</option>
                <option value="3gun">Son 3 Gün</option>
                <option value="7gun">Son 7 Gün</option>
                <option value="14gun">Son 14 Gün</option>
                <option value="30gun">Son 30 Gün</option>
              </select>
            </div>

            {/* Durum */}
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Durum
              </label>
              <div className="flex items-center space-x-4">
                <label className="flex items-center">
                  <input
                    type="radio"
                    name="archive-status"
                    checked={!showArchived}
                    onChange={() => setShowArchived(false)}
                    className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span className="ml-2 text-sm text-gray-700">Aktif</span>
                </label>
                <label className="flex items-center">
                  <input
                    type="radio"
                    name="archive-status"
                    checked={showArchived}
                    onChange={() => setShowArchived(true)}
                    className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  />
                  <span className="ml-2 text-sm text-gray-700">Arşiv</span>
                </label>
              </div>
            </div>

            {/* Filtreleri Temizle */}
            <div className="flex items-end">
              <button
                onClick={clearAllFilters}
                className="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              >
                Tüm Filtreleri Temizle
              </button>
            </div>
          </div>
        </div>

        {/* Desktop Table View */}
        <div className="hidden md:block bg-white shadow rounded-lg overflow-hidden">
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bölge
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bölge Mimarı
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bayi İsmi
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Mağaza Bilgisi
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Şehir/İlçe
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Talep Türü
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aşama
                  </th>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aşama Tarihi
                  </th>
                  <th scope="col" className="relative px-6 py-3">
                    <span className="sr-only">İşlemler</span>
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredTalepler.length === 0 ? (
                  <tr>
                    <td colSpan="9" className="px-6 py-12 text-center text-sm text-gray-500">
                      {talepler.length === 0 ? 'Henüz talep bulunmuyor' : 'Filtrelere uygun talep bulunamadı'}
                    </td>
                  </tr>
                ) : (
                  filteredTalepler.map((talep) => (
                    <tr key={talep.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {talep.bolge?.ad || '-'}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {talep.bolge_mimari?.ad_soyad || '-'}
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-medium text-gray-900">{talep.bayi?.ad || '-'}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">{talep.magaza_adi || '-'}</div>
                        <div className="text-xs text-gray-500">
                          {talep.magaza_tipi === 'kendi_magazasi' ? 'Kendi Mağazası' : 'Tali Bayi'}
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">{talep.sehir?.ad || '-'}</div>
                        <div className="text-xs text-gray-500">{talep.ilce?.ad || '-'}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-medium text-gray-900">{talep.talep_turu?.ad || '-'}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm font-medium text-gray-900">{talep.guncel_asama?.ad || '-'}</div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="text-sm text-gray-900">
                          {talep.guncel_asama_tarihi ? new Date(talep.guncel_asama_tarihi).toLocaleDateString('tr-TR') : '-'}
                        </div>
                        <div className="text-xs text-gray-500">
                          {talep.guncel_asama_tarihi ? new Date(talep.guncel_asama_tarihi).toLocaleTimeString('tr-TR', {
                            hour: '2-digit',
                            minute: '2-digit'
                          }) : '-'}
                        </div>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div className="flex items-center space-x-2">
                          <button
                            className="text-blue-600 hover:text-blue-900 p-1"
                            onClick={() => handleViewTalep(talep.id)}
                            title="Detay"
                          >
                            <EyeIcon className="h-4 w-4" />
                          </button>
                          <button
                            className="text-green-600 hover:text-green-900 p-1"
                            onClick={() => handleEditTalep(talep)}
                            title="Düzenle"
                          >
                            <PencilIcon className="h-4 w-4" />
                          </button>
                          <button
                            className="text-red-600 hover:text-red-900 p-1"
                            onClick={() => handleDeleteTalep(talep.id)}
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
          </div>
        </div>

        {/* Mobile Card View */}
        <div className="md:hidden space-y-4">
          {filteredTalepler.length === 0 ? (
            <div className="bg-white shadow rounded-lg p-6 text-center text-sm text-gray-500">
              {talepler.length === 0 ? 'Henüz talep bulunmuyor' : 'Filtrelere uygun talep bulunamadı'}
            </div>
          ) : (
            filteredTalepler.map((talep) => (
              <div key={talep.id} className="bg-white shadow rounded-lg p-4 hover:shadow-md transition-shadow">
                {/* Header */}
                <div className="flex justify-between items-start mb-3">
                  <div>
                    <h3 className="text-lg font-medium text-gray-900">{talep.bayi?.ad || '-'}</h3>
                    <p className="text-sm text-gray-600">{talep.bolge?.ad || '-'}</p>
                  </div>
                  <div className="flex items-center space-x-2">
                    <button
                      className="text-blue-600 hover:text-blue-900 p-1"
                      onClick={() => handleViewTalep(talep.id)}
                      title="Detay"
                    >
                      <EyeIcon className="h-4 w-4" />
                    </button>
                    <button
                      className="text-green-600 hover:text-green-900 p-1"
                      onClick={() => handleEditTalep(talep)}
                      title="Düzenle"
                    >
                      <PencilIcon className="h-4 w-4" />
                    </button>
                    <button
                      className="text-red-600 hover:text-red-900 p-1"
                      onClick={() => handleDeleteTalep(talep.id)}
                      title="Sil"
                    >
                      <TrashIcon className="h-4 w-4" />
                    </button>
                  </div>
                </div>

                {/* Content */}
                <div className="grid grid-cols-2 gap-3 text-sm">
                  <div>
                    <span className="font-medium text-gray-700">Bölge Mimarı:</span>
                    <p className="text-gray-900">{talep.bolge_mimari?.ad_soyad || '-'}</p>
                  </div>
                  <div>
                    <span className="font-medium text-gray-700">Şehir/İlçe:</span>
                    <p className="text-gray-900">{talep.sehir?.ad || '-'}</p>
                    <p className="text-xs text-gray-500">{talep.ilce?.ad || '-'}</p>
                  </div>
                  <div>
                    <span className="font-medium text-gray-700">Mağaza:</span>
                    <p className="text-gray-900">{talep.magaza_adi || '-'}</p>
                    <p className="text-xs text-gray-500">
                      {talep.magaza_tipi === 'kendi_magazasi' ? 'Kendi Mağazası' : 'Tali Bayi'}
                    </p>
                  </div>
                  <div>
                    <span className="font-medium text-gray-700">Talep Türü:</span>
                    <p className="text-gray-900">{talep.talep_turu?.ad || '-'}</p>
                  </div>
                </div>

                {/* Footer */}
                <div className="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                  <div>
                    <span className="font-medium text-gray-700">Aşama:</span>
                    <span className="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      {talep.guncel_asama?.ad || '-'}
                    </span>
                  </div>
                  <div className="text-right">
                    <p className="text-sm text-gray-900">
                      {talep.guncel_asama_tarihi ? new Date(talep.guncel_asama_tarihi).toLocaleDateString('tr-TR') : '-'}
                    </p>
                    <p className="text-xs text-gray-500">
                      {talep.guncel_asama_tarihi ? new Date(talep.guncel_asama_tarihi).toLocaleTimeString('tr-TR', {
                        hour: '2-digit',
                        minute: '2-digit'
                      }) : '-'}
                    </p>
                  </div>
                </div>
              </div>
            ))
          )}
        </div>

        {/* Pagination */}
        {pagination.total > 0 && (
          <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
            <div className="flex-1 flex justify-between sm:hidden">
              <button
                onClick={() => handlePageChange(currentPage - 1)}
                disabled={currentPage <= 1}
                className="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <ChevronLeftIcon className="h-4 w-4 mr-1" />
                Önceki
              </button>
              <button
                onClick={() => handlePageChange(currentPage + 1)}
                disabled={currentPage >= pagination.last_page}
                className="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Sonraki
                <ChevronRightIcon className="h-4 w-4 ml-1" />
              </button>
            </div>
            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
              <div>
                <p className="text-sm text-gray-700">
                  <span className="font-medium">{pagination.from || 0}</span> - <span className="font-medium">{pagination.to || 0}</span> arası,
                  toplam <span className="font-medium">{pagination.total || 0}</span> talep
                  {showArchived && <span className="text-blue-600 font-medium"> (Arşiv)</span>}
                </p>
              </div>
              <div>
                <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button
                    onClick={() => handlePageChange(currentPage - 1)}
                    disabled={currentPage <= 1}
                    className="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span className="sr-only">Önceki</span>
                    <ChevronLeftIcon className="h-5 w-5" />
                  </button>
                  
                  {/* Page numbers */}
                  {Array.from({ length: Math.min(5, pagination.last_page || 1) }, (_, i) => {
                    const pageNum = i + 1;
                    return (
                      <button
                        key={pageNum}
                        onClick={() => handlePageChange(pageNum)}
                        className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium ${
                          currentPage === pageNum
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                        }`}
                      >
                        {pageNum}
                      </button>
                    );
                  })}
                  
                  <button
                    onClick={() => handlePageChange(currentPage + 1)}
                    disabled={currentPage >= pagination.last_page}
                    className="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <span className="sr-only">Sonraki</span>
                    <ChevronRightIcon className="h-5 w-5" />
                  </button>
                </nav>
              </div>
            </div>
          </div>
        )}

        {/* Özet Bilgiler */}
        {filteredTalepler.length > 0 && (
          <div className="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 className="text-sm font-medium text-blue-800 mb-2">
              {showArchived ? 'Arşivlenmiş Talepler' : 'Aktif Talepler'} - Özet
            </h3>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-700">
              <div>
                <span className="font-medium">Toplam: </span>
                {pagination.total || filteredTalepler.length}
              </div>
              <div>
                <span className="font-medium">Sayfa: </span>
                {pagination.current_page || 1} / {pagination.last_page || 1}
              </div>
              <div>
                <span className="font-medium">Durum: </span>
                {showArchived ? 'Arşiv Görünümü' : 'Aktif Görünüm'}
              </div>
            </div>
          </div>
        )}

      </div>
    </Layout>
  );
};

export default Talepler;