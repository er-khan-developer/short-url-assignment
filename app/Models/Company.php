<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{CompanyUser};



class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_owned',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'company_users',
            'company_id',
            'user_id'
        )->withPivot('role');
    }

    public function companyUsers()
    {
        return $this->hasMany(
            CompanyUser::class);
    }

}
