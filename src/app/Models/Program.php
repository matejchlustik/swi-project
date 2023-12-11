<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'short',
        'major_id'
    ];

    public $timestamps = false;
    
    public function major() :BelongsTo
    {
        return $this->belongsTo(Majors::class);
    }
}
