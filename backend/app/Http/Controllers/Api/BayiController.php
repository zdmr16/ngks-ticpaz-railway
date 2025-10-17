<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bayi;
use App\Models\BayiCalisani;
use App\Models\BayiMagazasi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BayiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Bayi::with(['sehir', 'ilce', 'calisanlar']);
            
            // Şehir ID'ye göre filtreleme (cascade filtering için)
            if ($request->has('sehir_id')) {
                $query->where('sehir_id', $request->sehir_id);
            }
            
            $bayiler = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $bayiler
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bayiler listesi alınamadı.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Bayi oluşturma başladı

        $validator = Validator::make($request->all(), [
            'ad' => 'required|string|max:200',
            'sahip_adi' => 'nullable|string|max:100',
            'sahip_telefon' => 'nullable|string|max:20',
            'sahip_email' => 'nullable|email|max:100',
            'sehir_id' => 'required|exists:sehirler,id',
            'ilce_id' => 'required|exists:ilceler,id',
            'calisanlar' => 'array',
            'calisanlar.*.ad' => 'nullable|string|max:50',
            'calisanlar.*.soyad' => 'nullable|string|max:50',
            'calisanlar.*.telefon' => 'nullable|string|max:20',
            'calisanlar.*.email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validation başarılı

        try {
            DB::beginTransaction();

            // Veritabanı kayıt verilerini hazırla
            $bayiData = [
                'ad' => $request->ad,
                'sahip_adi' => $request->sahip_adi ?? '',
                'sahip_telefon' => $request->sahip_telefon ?? '',
                'sahip_email' => $request->sahip_email ?? '',
                'sehir_id' => $request->sehir_id,
                'ilce_id' => $request->ilce_id,
            ];

            // Bayi data hazırlandı

            $bayi = Bayi::create($bayiData);

            // Çalışanları ekle
            if ($request->has('calisanlar') && is_array($request->calisanlar)) {
                foreach ($request->calisanlar as $calisan) {
                    if (!empty($calisan['ad']) || !empty($calisan['soyad'])) {
                        BayiCalisani::create([
                            'bayi_id' => $bayi->id,
                            'ad_soyad' => trim(($calisan['ad'] ?? '') . ' ' . ($calisan['soyad'] ?? '')),
                            'telefon' => $calisan['telefon'] ?? '',
                            'email' => $calisan['email'] ?? '',
                        ]);
                    }
                }
            }

            DB::commit();

            // İlişkilerle beraber döndür
            $bayi->load(['sehir', 'ilce', 'calisanlar']);

            return response()->json([
                'success' => true,
                'message' => 'Bayi başarıyla oluşturuldu.',
                'data' => $bayi
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Bayi oluşturulurken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $bayi = Bayi::with(['sehir', 'ilce', 'calisanlar'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $bayi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bayi bulunamadı.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'ad' => 'required|string|max:200',
            'sahip_adi' => 'nullable|string|max:100',
            'sahip_telefon' => 'nullable|string|max:20',
            'sahip_email' => 'nullable|email|max:100',
            'sehir_id' => 'required|exists:sehirler,id',
            'ilce_id' => 'required|exists:ilceler,id',
            'calisanlar' => 'array',
            'calisanlar.*.ad' => 'nullable|string|max:50',
            'calisanlar.*.soyad' => 'nullable|string|max:50',
            'calisanlar.*.telefon' => 'nullable|string|max:20',
            'calisanlar.*.email' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $bayi = Bayi::findOrFail($id);
            
            $bayi->update([
                'ad' => $request->ad,
                'sahip_adi' => $request->sahip_adi,
                'sahip_telefon' => $request->sahip_telefon,
                'sahip_email' => $request->sahip_email,
                'sehir_id' => $request->sehir_id,
                'ilce_id' => $request->ilce_id,
            ]);

            // Mevcut çalışanları sil ve yeniden ekle
            $bayi->calisanlar()->delete();

            if ($request->has('calisanlar') && is_array($request->calisanlar)) {
                foreach ($request->calisanlar as $calisan) {
                    if (!empty($calisan['ad']) || !empty($calisan['soyad'])) {
                        BayiCalisani::create([
                            'bayi_id' => $bayi->id,
                            'ad_soyad' => trim(($calisan['ad'] ?? '') . ' ' . ($calisan['soyad'] ?? '')),
                            'telefon' => $calisan['telefon'] ?? '',
                            'email' => $calisan['email'] ?? '',
                        ]);
                    }
                }
            }

            DB::commit();

            // İlişkilerle beraber döndür
            $bayi->load(['sehir', 'ilce', 'calisanlar']);

            return response()->json([
                'success' => true,
                'message' => 'Bayi başarıyla güncellendi.',
                'data' => $bayi
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Bayi güncellenirken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $bayi = Bayi::findOrFail($id);
            $bayi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bayi başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bayi silinirken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bayi çalışanları
     */
    public function getCalisanlar($id)
    {
        try {
            $bayi = Bayi::findOrFail($id);
            $calisanlar = $bayi->calisanlar;

            return response()->json([
                'success' => true,
                'data' => $calisanlar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Çalışanlar listesi alınamadı.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bayi mağazaları
     */
    public function getMagazalar($bayiId)
    {
        try {
            // Bayi mağazaları API çağrısı yapıldı

            $bayi = Bayi::find($bayiId);
            if (!$bayi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bayi bulunamadı'
                ], 404);
            }

            $magazalar = BayiMagazasi::where('bayi_id', $bayiId)
                ->orderBy('ad')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $magazalar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mağazalar yüklenemedi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add mağaza to bayi
     */
    public function addMagaza(Request $request, $bayiId)
    {
        $validator = Validator::make($request->all(), [
            'ad' => 'required|string|max:200',
            'aciklama' => 'nullable|string|max:500',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $bayi = Bayi::findOrFail($bayiId);
            
            $magaza = BayiMagazasi::create([
                'bayi_id' => $bayiId,
                'ad' => $request->ad,
                'aciklama' => $request->aciklama ?? '',
                'aktif' => $request->aktif ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mağaza başarıyla eklendi.',
                'data' => $magaza
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mağaza eklenirken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update mağaza
     */
    public function updateMagaza(Request $request, $bayiId, $magazaId)
    {
        $validator = Validator::make($request->all(), [
            'ad' => 'required|string|max:200',
            'aciklama' => 'nullable|string|max:500',
            'aktif' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $magaza = BayiMagazasi::where('bayi_id', $bayiId)
                ->where('id', $magazaId)
                ->firstOrFail();
            
            $magaza->update([
                'ad' => $request->ad,
                'aciklama' => $request->aciklama ?? '',
                'aktif' => $request->aktif ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mağaza başarıyla güncellendi.',
                'data' => $magaza
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mağaza güncellenirken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete mağaza
     */
    public function deleteMagaza($bayiId, $magazaId)
    {
        try {
            $magaza = BayiMagazasi::where('bayi_id', $bayiId)
                ->where('id', $magazaId)
                ->firstOrFail();
            
            $magaza->delete();

            return response()->json([
                'success' => true,
                'message' => 'Mağaza başarıyla silindi.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Mağaza silinirken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}