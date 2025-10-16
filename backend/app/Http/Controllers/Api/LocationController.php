<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bolge;
use App\Models\Sehir;
use App\Models\Ilce;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Tüm bölgeleri getir
     */
    public function getBolgeler()
    {
        try {
            $bolgeler = Bolge::with('sehirler')->get();
            
            return response()->json([
                'success' => true,
                'data' => $bolgeler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölgeler getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tüm şehirleri getir
     */
    public function getSehirler(Request $request)
    {
        try {
            $query = Sehir::with(['bolge', 'ilceler']);
            
            // Bölge ID'ye göre filtreleme
            if ($request->has('bolge_id')) {
                $query->where('bolge_id', $request->bolge_id);
            }
            
            $sehirler = $query->orderBy('ad')->get();
            
            return response()->json([
                'success' => true,
                'data' => $sehirler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Şehirler getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Belirli bölgenin şehirlerini getir
     */
    public function getBolgeSehirleri($bolgeId)
    {
        try {
            $bolge = Bolge::with('sehirler.ilceler')->find($bolgeId);
            
            if (!$bolge) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bölge bulunamadı'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'bolge' => $bolge->ad,
                    'sehirler' => $bolge->sehirler
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge şehirleri getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tüm ilçeleri getir
     */
    public function getIlceler(Request $request)
    {
        try {
            $query = Ilce::with('sehir.bolge');
            
            // Şehir ID'ye göre filtreleme
            if ($request->has('sehir_id')) {
                $query->where('sehir_id', $request->sehir_id);
            }
            
            $ilceler = $query->orderBy('ad')->get();
            
            return response()->json([
                'success' => true,
                'data' => $ilceler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'İlçeler getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Belirli şehrin ilçelerini getir
     */
    public function getSehirIlceleri($sehirId)
    {
        try {
            $sehir = Sehir::with(['ilceler', 'bolge'])->find($sehirId);
            
            if (!$sehir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Şehir bulunamadı'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'sehir' => $sehir->ad,
                    'bolge' => $sehir->bolge->ad,
                    'ilceler' => $sehir->ilceler
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Şehir ilçeleri getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bölge detayını getir
     */
    public function getBolgeDetay($bolgeId)
    {
        try {
            $bolge = Bolge::with('sehirler.ilceler')->find($bolgeId);
            
            if (!$bolge) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bölge bulunamadı'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $bolge
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge detayı getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Şehir detayını getir
     */
    public function getSehirDetay($sehirId)
    {
        try {
            $sehir = Sehir::with(['bolge', 'ilceler'])->find($sehirId);
            
            if (!$sehir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Şehir bulunamadı'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $sehir
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Şehir detayı getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiyerarşik lokasyon yapısını getir (bölge > şehir > ilçe)
     */
    public function getHiyerarsi()
    {
        try {
            $bolgeler = Bolge::with('sehirler.ilceler')
                ->orderBy('ad')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $bolgeler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiyerarşi getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bölge mimarlarını getir
     */
    public function getBolgeMimarlari(Request $request)
    {
        try {
            \Log::info('🔍 Bölge mimarları API çağrısı', [
                'bolge_id' => $request->bolge_id,
                'has_bolge_id' => $request->has('bolge_id'),
                'all_request_params' => $request->all()
            ]);

            // Eğer bölge filtrelemesi varsa, SQL join ile filtreleme yap
            if ($request->has('bolge_id')) {
                \Log::info('🔹 Bölge ID ile filtreleme yapılıyor', ['bolge_id' => $request->bolge_id]);
                
                $sql = \DB::table('bolge_mimarlari as bm')
                    ->join('bolge_mimar_atamalari as bma', 'bm.id', '=', 'bma.bolge_mimari_id')
                    ->where('bma.bolge_id', $request->bolge_id)
                    ->select('bm.*')
                    ->orderBy('bm.ad_soyad');
                
                \Log::info('🔹 SQL QUERY:', ['sql' => $sql->toSql(), 'bindings' => $sql->getBindings()]);
                
                $bolgeMimarlari = $sql->get();
                
                \Log::info('🔹 RAW SQL SONUCU:', ['count' => $bolgeMimarlari->count(), 'data' => $bolgeMimarlari->toArray()]);
                
                // Model instance'larına çevir ve bolgeler ilişkisini yükle
                $bolgeMimarlari = collect($bolgeMimarlari)->map(function($item) {
                    $model = \App\Models\BolgeMimari::find($item->id);
                    $model->load('bolgeler');
                    return $model;
                });
            } else {
                // Filtreleme yoksa hepsini getir
                $bolgeMimarlari = \App\Models\BolgeMimari::with('bolgeler')->orderBy('ad_soyad')->get();
            }
            
            \Log::info('✅ FINAL RESULT:', ['count' => $bolgeMimarlari->count(), 'data' => $bolgeMimarlari->toArray()]);
            
            return response()->json([
                'success' => true,
                'data' => $bolgeMimarlari
            ]);
        } catch (\Exception $e) {
            \Log::error('Bölge mimarları getirme hatası', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarları getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bayileri getir
     */
    public function getBayiler(Request $request)
    {
        try {
            $query = \DB::table('bayiler');
            
            // Şehir ID'ye göre filtreleme
            if ($request->has('sehir_id')) {
                $query->where('sehir_id', $request->sehir_id);
            }
            
            $bayiler = $query->orderBy('ad')->get();
            
            return response()->json([
                'success' => true,
                'data' => $bayiler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bayiler getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Talep türlerini getir
     */
    public function getTalepTurleri()
    {
        try {
            $talepTurleri = \DB::table('talep_turleri')
                ->orderBy('ad')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $talepTurleri
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Talep türleri getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aşamaları getir
     */
    public function getAsamalar(Request $request)
    {
        try {
            $query = \DB::table('asamalar');
            
            // İş akışı tipine göre filtreleme
            if ($request->has('is_akisi_tipi')) {
                $query->where('is_akisi_tipi', $request->is_akisi_tipi);
            }
            
            // Talep türü ID'sine göre filtreleme (düzenleme için)
            if ($request->has('talep_turu_id')) {
                $talepTuru = \DB::table('talep_turleri')->find($request->talep_turu_id);
                if ($talepTuru) {
                    $query->where('is_akisi_tipi', $talepTuru->is_akisi_tipi);
                    // Sadece düzenleme için - sıra 0 olanları (Talep Oluşturuldu) hariç tut
                    $query->where('sira', '>', 0);
                }
            }
            
            $asamalar = $query->orderBy('sira')->get();
            
            return response()->json([
                'success' => true,
                'data' => $asamalar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Aşamalar getirilemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
