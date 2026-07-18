<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Invitation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'role',
        'company_id',
        'token',
        'invited_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
}
