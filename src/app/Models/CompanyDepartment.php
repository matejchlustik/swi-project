<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyDepartment extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'departments_id',
        'companies_id',

    ];
    public function practiceOffers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PracticeOffer::class);
    }

}
