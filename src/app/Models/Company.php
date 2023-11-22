<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function companyEmployee() :HasMany
    {
        return $this->hasMany(CompanyEmployee::class);
    }
}
