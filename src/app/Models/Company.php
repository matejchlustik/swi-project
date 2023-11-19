<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function departments()
    {
        return $this->belongsToMany(Department::class)->using(CompanyDepartment::class);
        //return $this->belongsToMany(Department::class, 'company_department', 'company_id', 'department_id');
    }
    public function companyEmployee()
    {
        return $this->hasMany(CompanyEmployee::class);
    }
}
