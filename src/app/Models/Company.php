<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'ICO',
        'name',
        'city',
        'ZIP_code',
        'phone',
        'email',
        'street',
        'house_number'
    ];
    protected $guarded = ['id'];
    public function departments()
    {
        //return $this->belongsToMany(Department::class)->using(CompanyDepartment::class);
        return $this->belongsToMany(Department::class)->using(CompanyDepartment::class);
    }
    public function companyEmployee()
    {
        return $this->hasMany(CompanyEmployee::class);
    }
    public function practiceOffers()
    {
        return $this->hasManyThrough(PracticeOffer::class, CompanyDepartment::class,"company_id","company_department_id","id","id");
    }

}
