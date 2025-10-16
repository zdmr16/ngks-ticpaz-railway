<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bolge extends Model
{
    use HasFactory;
    
    protected $table = 'bolgeler';
    
    protected $fillable = [
        'ad'
    ];
    
    public function sehirler()
    {
        return $this->hasMany(Sehir::class, 'bolge_id');
    }
}
