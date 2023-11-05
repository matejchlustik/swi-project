<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
