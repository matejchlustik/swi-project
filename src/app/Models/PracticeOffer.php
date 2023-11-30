<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'phone',
        'email',
        'company_department_id'
    ];
    public function companyDepartment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CompanyDepartment::class);
    }

}
