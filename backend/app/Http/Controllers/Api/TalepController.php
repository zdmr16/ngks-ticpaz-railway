<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Talep;
use App\Models\TalepTuru;
use App\Models\TalepAsamaGecmisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TalepController extends Controller
{
    /**
     * Talepler listesini getir
     */
    public function index(Request $request)
    {
        try {
            // Pagination parametreleri
            $perPage = $request->get('per_page', 25);
            $page = $request->get('page', 1);
            
            // Arşiv filtresi
            $arsiv = $request->get('arsiv', false);

            // Query builder
            $query = Talep::with([
                'bolge:id,ad',
                'bolgeMimari:id,ad_soyad,email,telefon',
                'bayi:id,ad,sahip_adi,sahip_telefon,sahip_email',
                'sehir:id,ad',
                'ilce:id,ad',
                'talepTuru:id,ad,is_akisi_tipi',
                'guncelAsama:id,ad,sira'
            ]);

            // Arşiv filtreleme
            if ($arsiv) {
                $query->arsivlenmis();
            } else {
                $query->aktif();
            }

            // Filtreleme
            if ($request->has('bolge_id')) {
                $query->where('bolge_id', $request->bolge_id);
            }
            
            if ($request->has('sehir_id')) {
                $query->where('sehir_id', $request->sehir_id);
            }
            
            if ($request->has('talep_turu_id')) {
                $query->where('talep_turu_id', $request->talep_turu_id);
            }
            
            if ($request->has('magaza_tipi')) {
                $query->where('magaza_tipi', $request->magaza_tipi);
            }

            // Sıralama
            $query->orderBy('created_at', 'desc');

            // Pagination ile sonuçları al
            $talepler = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Talepler başarıyla getirildi',
                'data' => $talepler->items(),
                'pagination' => [
                    'total' => $talepler->total(),
                    'per_page' => $talepler->perPage(),
                    'current_page' => $talepler->currentPage(),
                    'last_page' => $talepler->lastPage(),
                    'from' => $talepler->firstItem(),
                    'to' => $talepler->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Talepler yüklenirken bir hata oluştu',
                'error' => config('app.debug') ? $e->getMessage() : 'Sunucu hatası'
            ], 500);
        }
    }

    /**
     * Tek talep detayını getir
     */
    public function show($id)
    {
        try {
            $talep = Talep::with([
                'bolge:id,ad',
                'bolgeMimari:id,ad_soyad,email,telefon',
                'bayi:id,ad,sahip_adi,sahip_telefon,sahip_email',
                'sehir:id,ad',
                'ilce:id,ad',
                'talepTuru:id,ad,is_akisi_tipi',
                'guncelAsama:id,ad,sira',
                'asamaGecmisi.asama:id,ad,sira',
                'asamaGecmisi.degistirenKullanici:id,ad_soyad'
            ])->find($id);

            if (!$talep) {
                return response()->json([
                    'success' => false,
                    'message' => 'Talep bulunamadı'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Talep detayı getirildi',
                'data' => $talep
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Talep detayı yüklenirken hata oluştu'
            ], 500);
        }
    }

    /**
     * Yeni talep oluştur
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'bolge_id' => 'required|exists:bolgeler,id',
                'bolge_mimari_id' => 'required|exists:bolge_mimarlari,id',
                'bayi_id' => 'required|exists:bayiler,id',
                'magaza_tipi' => 'required|in:kendi_magazasi,tali_bayi',
                'magaza_adi' => 'required|string|max:200',
                'sehir_id' => 'required|exists:sehirler,id',
                'ilce_id' => 'required|exists:ilceler,id',
                'talep_turu_id' => 'required|exists:talep_turleri,id',
                'aciklama' => 'required|string'
            ]);

            // Talep türüne göre "Talep Oluşturuldu" aşamasını bul (sıra = 0)
            $talepTuru = TalepTuru::find($validated['talep_turu_id']);
            $ilkAsama = \App\Models\Asama::where('is_akisi_tipi', $talepTuru->is_akisi_tipi)
                ->where('sira', 0)
                ->first();

            if (!$ilkAsama) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu talep türü için aşama bulunamadı'
                ], 400);
            }

            DB::beginTransaction();

            // Yeni talep oluştur
            $talep = Talep::create([
                'bolge_id' => $validated['bolge_id'],
                'bolge_mimari_id' => $validated['bolge_mimari_id'],
                'bayi_id' => $validated['bayi_id'],
                'magaza_tipi' => $validated['magaza_tipi'],
                'magaza_adi' => $validated['magaza_adi'],
                'sehir_id' => $validated['sehir_id'],
                'ilce_id' => $validated['ilce_id'],
                'talep_turu_id' => $validated['talep_turu_id'],
                'aciklama' => $validated['aciklama'], // ✅ Doğru alan
                'guncel_asama_id' => $ilkAsama->id,
                'guncel_asama_tarihi' => now(),
                'guncel_asama_aciklamasi' => 'Talep oluşturuldu', // ✅ Sistem mesajı
                'arsivlendi_mi' => false
            ]);

            // Aşama geçmişi kaydı oluştur
            TalepAsamaGecmisi::create([
                'talep_id' => $talep->id,
                'asama_id' => $ilkAsama->id,
                'aciklama' => $validated['aciklama'],
                'degistirilme_tarihi' => now(),
                'degistiren_kullanici_id' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Talep başarıyla oluşturuldu',
                'data' => ['id' => $talep->id]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Talep oluşturulurken hata oluştu',
                'error' => config('app.debug') ? $e->getMessage() : 'Sunucu hatası'
            ], 500);
        }
    }

    /**
     * Talebi güncelle
     */
    public function update(Request $request, $id)
    {
        try {
            // Validation
            $validated = $request->validate([
                'bolge_id' => 'required|exists:bolgeler,id',
                'bolge_mimari_id' => 'required|exists:bolge_mimarlari,id',
                'bayi_id' => 'required|exists:bayiler,id',
                'magaza_tipi' => 'required|in:kendi_magazasi,tali_bayi',
                'magaza_adi' => 'required|string|max:200',
                'sehir_id' => 'required|exists:sehirler,id',
                'ilce_id' => 'required|exists:ilceler,id',
                'talep_turu_id' => 'required|exists:talep_turleri,id'
            ]);

            // Talep var mı kontrol et
            $talep = Talep::find($id);
            if (!$talep) {
                return response()->json([
                    'success' => false,
                    'message' => 'Talep bulunamadı'
                ], 404);
            }

            // Talebi güncelle
            $talep->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Talep başarıyla güncellendi'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Talep güncellenirken hata oluştu',
                'error' => config('app.debug') ? $e->getMessage() : 'Sunucu hatası'
            ], 500);
        }
    }

    /**
     * Talebi sil
     */
    public function destroy($id)
    {
        try {
            // Talep var mı kontrol et
            $talep = Talep::find($id);
            if (!$talep) {
                return response()->json([
                    'success' => false,
                    'message' => 'Talep bulunamadı'
                ], 404);
            }

            DB::beginTransaction();

            // Aşama geçmişini sil
            TalepAsamaGecmisi::where('talep_id', $id)->delete();

            // Talebi sil
            $talep->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Talep başarıyla silindi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Talep silinirken hata oluştu',
                'error' => config('app.debug') ? $e->getMessage() : 'Sunucu hatası'
            ], 500);
        }
    }

    /**
     * Talep aşama geçmişini getir
     */
    public function asamaGecmisi($id)
    {
        try {
            $gecmis = TalepAsamaGecmisi::with([
                'asama:id,ad,sira',
                'degistirenKullanici:id,ad_soyad'
            ])
            ->where('talep_id', $id)
            ->orderBy('degistirilme_tarihi', 'desc')
            ->get();

            return response()->json([
                'success' => true,
                'data' => $gecmis
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Aşama geçmişi yüklenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Talep aşamasını değiştir
     */
    public function updateAsama(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'asama_id' => 'required|exists:asamalar,id',
                'aciklama' => 'required|string|max:1000'
            ]);

            $talep = Talep::find($id);
            if (!$talep) {
                return response()->json([
                    'success' => false,
                    'message' => 'Talep bulunamadı'
                ], 404);
            }

            DB::beginTransaction();

            // Talep aşamasını güncelle
            $talep->update([
                'guncel_asama_id' => $validated['asama_id'],
                'guncel_asama_tarihi' => now(),
                'guncel_asama_aciklamasi' => $validated['aciklama']
            ]);

            // Aşama geçmişine kaydet
            TalepAsamaGecmisi::create([
                'talep_id' => $id,
                'asama_id' => $validated['asama_id'],
                'aciklama' => $validated['aciklama'],
                'degistirilme_tarihi' => now(),
                'degistiren_kullanici_id' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Talep aşaması başarıyla güncellendi'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Aşama güncellenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Talebi arşivle
     */
    public function arsivle($id)
    {
        try {
            $talep = Talep::find($id);
            if (!$talep) {
                return response()->json([
                    'success' => false,
                    'message' => 'Talep bulunamadı'
                ], 404);
            }

            $talep->update(['arsivlendi_mi' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Talep başarıyla arşivlendi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arşivleme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
