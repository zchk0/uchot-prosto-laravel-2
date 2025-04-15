<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactDeal extends Model
{
    use HasFactory;

    protected $table = 'contact_deal';

    public $timestamps = false;
}
