<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BolgeMimari;
use App\Models\BolgeMimarAtamasi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BolgeMimariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $mimarlari = BolgeMimari::with(['atamalari.bolge'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Her mimarın bölge bilgilerini düzenle
            $mimarlari->transform(function ($mimar) {
                $bolge_atamalari = $mimar->atamalari->map(function ($atama) {
                    return [
                        'id' => $atama->bolge_id,
                        'ad' => $atama->bolge->ad ?? ''
                    ];
                });
                
                $mimar->bolgeler = $bolge_atamalari;
                unset($mimar->atamalari);
                
                return $mimar;
            });

            return response()->json([
                'success' => true,
                'data' => $mimarlari
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarları listesi alınamadı.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_soyad' => 'required|string|max:100',
            'email' => 'required|email|unique:bolge_mimarlari,email',
            'telefon' => 'nullable|string|max:20',
            'aktif' => 'boolean',
            'bolge_id' => 'required|exists:bolgeler,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mimar = BolgeMimari::create([
                'ad_soyad' => $request->ad_soyad,
                'email' => $request->email,
                'telefon' => $request->telefon,
                'aktif' => $request->aktif ?? true
            ]);

            // Bölge atamasını yap
            $mimar->bolgeler()->attach($request->bolge_id);

            // Response'u index metoduyla uyumlu hale getir
            $mimar->load(['atamalari.bolge']);
            $bolge_atamalari = $mimar->atamalari->map(function ($atama) {
                return [
                    'id' => $atama->bolge_id,
                    'ad' => $atama->bolge->ad ?? ''
                ];
            });
            $mimar->bolgeler = $bolge_atamalari;
            unset($mimar->atamalari);

            return response()->json([
                'success' => true,
                'message' => 'Bölge mimarı başarıyla oluşturuldu.',
                'data' => $mimar
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarı oluşturulurken hata oluştu.',
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
            $mimar = BolgeMimari::with(['atamalari.bolge'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $mimar
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarı bulunamadı.',
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
            'ad_soyad' => 'required|string|max:100',
            'email' => 'required|email|unique:bolge_mimarlari,email,' . $id,
            'telefon' => 'nullable|string|max:20',
            'aktif' => 'boolean',
            'bolge_id' => 'required|exists:bolgeler,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasyon hatası.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mimar = BolgeMimari::findOrFail($id);
            
            $mimar->update([
                'ad_soyad' => $request->ad_soyad,
                'email' => $request->email,
                'telefon' => $request->telefon,
                'aktif' => $request->aktif ?? true
            ]);

            // Mevcut bölge atamalarını temizle ve yeni atamasını yap
            $mimar->bolgeler()->sync([$request->bolge_id]);

            // Response'u index metoduyla uyumlu hale getir
            $mimar->load(['atamalari.bolge']);
            $bolge_atamalari = $mimar->atamalari->map(function ($atama) {
                return [
                    'id' => $atama->bolge_id,
                    'ad' => $atama->bolge->ad ?? ''
                ];
            });
            $mimar->bolgeler = $bolge_atamalari;
            unset($mimar->atamalari);

            return response()->json([
                'success' => true,
                'message' => 'Bölge mimarı başarıyla güncellendi.',
                'data' => $mimar
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarı güncellenirken hata oluştu.',
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
            $mimar = BolgeMimari::findOrFail($id);
            $mimar->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bölge mimarı başarıyla silindi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarı silinirken hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mimarlar by bölge
     */
    public function getByBolge($bolge_id)
    {
        try {
            $mimarlari = BolgeMimari::whereHas('atamalari', function ($query) use ($bolge_id) {
                $query->where('bolge_id', $bolge_id);
            })->get();

            return response()->json([
                'success' => true,
                'data' => $mimarlari
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bölge mimarları listesi alınamadı.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}