<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talep extends Model
{
    use HasFactory;

    protected $table = 'talepler';

    protected $fillable = [
        'bolge_id',
        'bolge_mimari_id',
        'bayi_id',
        'magaza_tipi',
        'magaza_adi',
        'sehir_id',
        'ilce_id',
        'talep_turu_id',
        'aciklama', // ✅ Eksik alan eklendi
        'guncel_asama_id',
        'guncel_asama_tarihi',
        'guncel_asama_aciklamasi',
        'arsivlendi_mi'
    ];

    protected $casts = [
        'guncel_asama_tarihi' => 'datetime',
        'arsivlendi_mi' => 'boolean'
    ];

    /**
     * Talebin ait olduğu bölge
     */
    public function bolge()
    {
        return $this->belongsTo(Bolge::class);
    }

    /**
     * Talebin atandığı bölge mimarı
     */
    public function bolgeMimari()
    {
        return $this->belongsTo(BolgeMimari::class);
    }

    /**
     * Talebin ait olduğu bayi
     */
    public function bayi()
    {
        return $this->belongsTo(Bayi::class);
    }

    /**
     * Talebin ait olduğu şehir
     */
    public function sehir()
    {
        return $this->belongsTo(Sehir::class);
    }

    /**
     * Talebin ait olduğu ilçe
     */
    public function ilce()
    {
        return $this->belongsTo(Ilce::class);
    }

    /**
     * Talebin türü
     */
    public function talepTuru()
    {
        return $this->belongsTo(TalepTuru::class);
    }

    /**
     * Talebin güncel aşaması
     */
    public function guncelAsama()
    {
        return $this->belongsTo(Asama::class, 'guncel_asama_id');
    }

    /**
     * Talebin aşama geçmişi
     */
    public function asamaGecmisi()
    {
        return $this->hasMany(TalepAsamaGecmisi::class)->orderBy('degistirilme_tarihi', 'desc');
    }

    /**
     * Aktif talepler (arşivlenmemiş)
     */
    public function scopeAktif($query)
    {
        return $query->where('arsivlendi_mi', false);
    }

    /**
     * Arşivlenmiş talepler
     */
    public function scopeArsivlenmis($query)
    {
        return $query->where('arsivlendi_mi', true);
    }

    /**
     * Belirli bir iş akışı tipine göre talepler
     */
    public function scopeByIsAkisiTipi($query, $tip)
    {
        return $query->whereHas('talepTuru', function($q) use ($tip) {
            $q->where('is_akisi_tipi', $tip);
        });
    }
}