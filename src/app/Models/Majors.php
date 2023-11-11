<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Majors extends Model
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
    public function program() :BelongsTo
    {
        return $this->belongsTo(program::class);
    }
}
