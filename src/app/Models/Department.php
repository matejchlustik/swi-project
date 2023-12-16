<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'short',
        'faculty_id'
    ];

    public $timestamps = false;
    public static function booted()
    {

        static::deleting(function ($department) {
            $department->major()->get()->each->delete();
            $department->departmentEmployee()->get()->each->delete();
            CompanyDepartment::where("department_id",$department->id)->get()->each->delete();
        });


        static::restored(function ($department) {
            $department->major()->withTrashed()->get()->each->restore();
            $department->departmentEmployee()->withTrashed()->get()->each->restore();
            CompanyDepartment::where("department_id",$department->id)->withTrashed()->get()->each->restore();
        });
    }
    public function faculty() :BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
    public function major() :HasMany
    {
        return $this->hasMany(Major::class,"department_id");
    }
    public function companies()
    {
        return $this->belongsToMany(Company::class)->using(CompanyDepartment::class);
        //return $this->belongsToMany(Company::class)->using(CompanyDepartment::class);
    }
    public function departmentEmployee() :HasMany
    {
        return $this->hasMany(DepartmentEmployee::class,'department_id');
    }
    public function practiceOffers() : HasManyThrough
    {
        return $this->hasManyThrough(PracticeOffer::class, CompanyDepartment::class,"department_id","company_department_id","id","id");
    }
    public function users() : HasManyThrough
    {
        return $this->hasManyThrough(User::class, DepartmentEmployee::class,"department_id","id","id","user_id");
    }
}
