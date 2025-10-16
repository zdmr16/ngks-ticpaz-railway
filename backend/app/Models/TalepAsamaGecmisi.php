<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalepAsamaGecmisi extends Model
{
    use HasFactory;

    protected $table = 'talep_asama_gecmisi';

    protected $fillable = [
        'talep_id',
        'asama_id',
        'aciklama',
        'degistirilme_tarihi',
        'degistiren_kullanici_id'
    ];

    protected $casts = [
        'degistirilme_tarihi' => 'datetime'
    ];

    /**
     * Bu geçmişin ait olduğu talep
     */
    public function talep()
    {
        return $this->belongsTo(Talep::class);
    }

    /**
     * Bu geçmişe ait aşama bilgisi
     */
    public function asama()
    {
        return $this->belongsTo(Asama::class);
    }

    /**
     * Bu değişikliği yapan kullanıcı
     */
    public function degistirenKullanici()
    {
        return $this->belongsTo(Kullanici::class, 'degistiren_kullanici_id');
    }
}