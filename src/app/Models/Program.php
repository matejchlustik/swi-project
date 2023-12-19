<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'short',
        'major_id'
    ];

    public $timestamps = false;

    public function major() :BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
    public function practices() :HasMany
    {
        return $this->hasMany(Practice::class);
    }
}
