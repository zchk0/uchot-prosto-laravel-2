<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
    ];

    /**
     * Связь многие-ко-многим с моделью Deal
     */
    public function deals()
    {
        return $this->belongsToMany(Deal::class, 'contact_deal')->withPivot('id');
    }
}
