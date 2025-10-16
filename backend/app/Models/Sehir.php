<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sehir extends Model
{
    use HasFactory;
    
    protected $table = 'sehirler';
    
    protected $fillable = [
        'ad',
        'bolge_id'
    ];
    
    public function bolge()
    {
        return $this->belongsTo(Bolge::class, 'bolge_id');
    }
    
    public function ilceler()
    {
        return $this->hasMany(Ilce::class, 'sehir_id');
    }
}
