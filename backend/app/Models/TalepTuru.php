<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalepTuru extends Model
{
    use HasFactory;

    protected $table = 'talep_turleri';

    protected $fillable = [
        'ad',
        'is_akisi_tipi'
    ];

    /**
     * Bu talep türüne ait aşamaları döner
     */
    public function asamalar()
    {
        return $this->hasMany(Asama::class, 'is_akisi_tipi', 'is_akisi_tipi');
    }

    /**
     * Bu talep türüne ait talepleri döner
     */
    public function talepler()
    {
        return $this->hasMany(Talep::class, 'talep_turu_id');
    }
}
