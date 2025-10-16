<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayiMagazasi extends Model
{
    use HasFactory;

    protected $table = 'bayi_magazalari';

    protected $fillable = [
        'bayi_id',
        'ad',
        'aciklama',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // İlişkiler
    public function bayi()
    {
        return $this->belongsTo(Bayi::class);
    }

    // Scope'lar
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeBayiye($query, $bayiId)
    {
        return $query->where('bayi_id', $bayiId);
    }
}
