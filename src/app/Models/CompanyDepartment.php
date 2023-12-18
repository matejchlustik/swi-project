<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyDepartment extends Pivot
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'department_id',
        'company_id',
    ];
    public static function booted()
    {
        static::deleting(function ($companyDepartment) {
            $companyDepartment->practiceOffers()->get()->each->delete();
        });
        static::restored(function ($companyDepartment) {
            $companyDepartment->practiceOffers()->withTrashed()->get()->each->restore();
        });
    }
    public function practiceOffers(): HasMany
    {
        return $this->hasMany(PracticeOffer::class,"company_department_id");
    }
    public function department() :BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function company() :BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

}
