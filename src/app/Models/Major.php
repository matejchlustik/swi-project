<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Major extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'department_id'
    ];

    public function department() :BelongsTo
    {
        return $this->belongsTo(department::class);
    }
    public function program() :HasMany
    {
        return $this->hasMany(program::class);
    }
}
