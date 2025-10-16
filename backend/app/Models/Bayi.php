<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bayi extends Model
{
    use HasFactory;

    protected $table = 'bayiler';

    protected $fillable = [
        'ad',
        'sahip_adi',
        'sahip_telefon',
        'sahip_email',
        'sehir_id',
        'ilce_id'
    ];

    // İl ilişkisi
    public function sehir()
    {
        return $this->belongsTo(Sehir::class, 'sehir_id');
    }

    // İlçe ilişkisi
    public function ilce()
    {
        return $this->belongsTo(Ilce::class, 'ilce_id');
    }

    // Bölge ilişkisi (şehir üzerinden)
    public function bolge()
    {
        return $this->hasOneThrough(Bolge::class, Sehir::class, 'id', 'id', 'sehir_id', 'bolge_id');
    }

    // Bayi çalışanları ilişkisi
    public function calisanlar()
    {
        return $this->hasMany(BayiCalisani::class, 'bayi_id');
    }

    // Talepler ilişkisi
    public function talepler()
    {
        return $this->hasMany(Talep::class, 'bayi_id');
    }

    // Mağazalar ilişkisi
    public function magazalar()
    {
        return $this->hasMany(BayiMagazasi::class, 'bayi_id');
    }

    // Aktif mağazalar
    public function aktifMagazalar()
    {
        return $this->hasMany(BayiMagazasi::class, 'bayi_id')->where('aktif', true);
    }
}