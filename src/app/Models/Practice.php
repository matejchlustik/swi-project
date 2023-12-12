<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Practice extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function practiceRecords() :HasMany
    {
        return $this->hasMany(PracticeRecord::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyEmployee() :BelongsTo
    {
        return $this->belongsTo(CompanyEmployee::class);
    }

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Company::class,
            CompanyEmployee::class,
            "id",
            "id",
            "company_employee_id",
            "company_id"
        );
    }

    public function program() :BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
    public function feedback() :HasMany
    {
        return $this->hasMany(Feedback::class);
    }
    public function comments() :HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function evaluations() :HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
