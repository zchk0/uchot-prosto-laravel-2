<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subtheme extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'theme_id', 
        'name', 
        'content'
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
