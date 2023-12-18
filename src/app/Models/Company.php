<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'ICO',
        'name',
        'city',
        'zip_code',
        'phone',
        'email',
        'street',
        'house_number'
    ];
    protected $guarded = ['id'];
    public static function booted()
    {
        static::deleting(function ($company) {
            CompanyDepartment::where("company_id",$company->id)->withTrashed()->get()->each->delete();
        });
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class)->using(CompanyDepartment::class);
    }
    public function companyEmployees() :HasMany
    {
        return $this->hasMany(CompanyEmployee::class,'company_id');
    }
    public function practiceOffers()
    {
        return $this->hasManyThrough(PracticeOffer::class, CompanyDepartment::class,"company_id","company_department_id","id","id");
    }

}
