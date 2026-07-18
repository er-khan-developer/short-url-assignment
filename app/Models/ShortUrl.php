<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortUrl extends Model
{   
    use HasFactory;
    protected $fillable = [
        'short_url',
        'original_url',
        'user_id',
        'company_id',
    ];
}
