<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curroute;

class Lang extends Model
{
    use HasFactory;
    
    protected $table = 'langs';

    protected $fillable = ['key', 'en', 'ka', 'gr', 'ru'];
    
    public function curroutes()
    {
        return $this->belongsToMany(Curroute::class);
    }
}
