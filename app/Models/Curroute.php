<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lang;

class Curroute extends Model
{
    use HasFactory;
    
    protected $table = 'curroutes';

    protected $fillable = ['name'];
    
    public function langs()
    {
        return $this->belongsToMany(Lang::class);
    }
}
