<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeOffers extends Model
{
    protected $fillable = ['description', 'phone', 'email', 'company_department_id'];

    public function companyDepartment()
    {
        return $this->belongsTo(CompanyDepartment::class);
    }

}
