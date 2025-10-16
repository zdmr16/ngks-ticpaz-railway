<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayiCalisani extends Model
{
    use HasFactory;

    protected $table = 'bayi_calisanlari';

    protected $fillable = [
        'bayi_id',
        'ad_soyad',
        'telefon',
        'email'
    ];

    // Bayi ilişkisi
    public function bayi()
    {
        return $this->belongsTo(Bayi::class, 'bayi_id');
    }
}