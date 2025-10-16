<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BolgeMimari extends Model
{
    use HasFactory;

    protected $table = 'bolge_mimarlari';

    protected $fillable = [
        'ad_soyad',
        'email',
        'telefon',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Atama ilişkisi - bir mimarın birden fazla bölge ataması olabilir
    public function atamalari()
    {
        return $this->hasMany(BolgeMimarAtamasi::class, 'bolge_mimari_id');
    }

    // Bölgeler ilişkisi - many-to-many through pivot table
    public function bolgeler()
    {
        return $this->belongsToMany(Bolge::class, 'bolge_mimar_atamalari', 'bolge_mimari_id', 'bolge_id');
    }

    // Aktif mimarları getir
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}