<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\{Company,User};

class CompanyUser extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
