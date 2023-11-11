<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'short',
        'major_id'
    ];
    public function majors() :BelongsTo
    {
        return $this->belongsTo(Majors::class);
    }
}
