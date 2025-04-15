<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount'
    ];

    /**
     * Связь многие-ко-многим с моделью Contact
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_deal')->withPivot('id');
    }
}
