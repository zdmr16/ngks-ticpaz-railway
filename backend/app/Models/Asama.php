<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asama extends Model
{
    use HasFactory;

    protected $table = 'asamalar';

    protected $fillable = [
        'is_akisi_tipi',
        'ad',
        'sira'
    ];

    /**
     * Bu aşamaya ait talep türlerini döner
     */
    public function talepTurleri()
    {
        return $this->hasMany(TalepTuru::class, 'is_akisi_tipi', 'is_akisi_tipi');
    }

    /**
     * Bu aşamadaki talep geçmişlerini döner
     */
    public function talepAsamaGecmisi()
    {
        return $this->hasMany(TalepAsamaGecmisi::class, 'asama_id');
    }

    /**
     * Aşamaları sıraya göre sıralar
     */
    public function scopeSiralama($query)
    {
        return $query->orderBy('sira');
    }

    /**
     * Belirli iş akışı tipine göre aşamaları getirir
     */
    public function scopeIsAkisiTipi($query, $tip)
    {
        return $query->where('is_akisi_tipi', $tip);
    }
}