<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompanyEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'phone'
    ];

    public $timestamps = false;

    public function practice() :HasMany
    {
        return $this->hasMany(Practice::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company() :BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
