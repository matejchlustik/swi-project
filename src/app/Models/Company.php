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
        return $this->belongsToMany('App\Models\Department', 'company_department', 'companies_id', 'id');
    }
    public function companyEmployee()
    {
        return $this->hasMany(CompanyEmployee::class);
    }
    public function practiceOffers()
    {
        return $this->hasManyThrough(PracticeOffer::class, CompanyDepartment::class);
    }

}
