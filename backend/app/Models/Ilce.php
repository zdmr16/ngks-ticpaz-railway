<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ilce extends Model
{
    use HasFactory;

    protected $table = 'ilceler';

    protected $fillable = [
        'sehir_id',
        'ad'
    ];

    /**
     * Bu ilçenin ait olduğu şehri döner
     */
    public function sehir()
    {
        return $this->belongsTo(Sehir::class, 'sehir_id');
    }

    /**
     * Bu ilçedeki bayileri döner
     */
    public function bayiler()
    {
        return $this->hasMany(Bayi::class, 'ilce_id');
    }

    /**
     * Belirli şehre ait ilçeleri getirir
     */
    public function scopeSehir($query, $sehirId)
    {
        return $query->where('sehir_id', $sehirId);
    }
}
