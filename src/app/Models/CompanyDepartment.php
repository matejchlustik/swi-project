<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyDepartment extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'company_id',

    ];
    public function practiceOffers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PracticeOffer::class);
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
