<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BolgeMimarAtamasi extends Model
{
    use HasFactory;

    protected $table = 'bolge_mimar_atamalari';

    protected $fillable = [
        'bolge_id',
        'bolge_mimari_id'
    ];

    // Bölge ilişkisi
    public function bolge()
    {
        return $this->belongsTo(Bolge::class, 'bolge_id');
    }

    // Bölge mimarı ilişkisi
    public function bolgeMimari()
    {
        return $this->belongsTo(BolgeMimari::class, 'bolge_mimari_id');
    }
}