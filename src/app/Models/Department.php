<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short',
        'faculty_id'
    ];

    public function faculty() :BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }
    public function majors() :HasMany
    {
        return $this->hasMany(Majors::class);
    }
    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'company_department', 'departments_id', 'id');
        //return $this->belongsToMany(Company::class)->using(CompanyDepartment::class);
    }
    public function departmentEmployee()
    {
        return $this->hasMany(DepartmentEmployee::class);
    }
}
