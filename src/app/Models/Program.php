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
    public static function booted()
    {
        static::deleting(function ($program) {
            $program->practices()->get()->each->delete();
        });
        static::restored(function ($program) {
            $program->practice()->withTrashed()->get()->each->restore();
        });
    }
    public function major() :BelongsTo
    {
        return $this->belongsTo(Majors::class);
    }
    public function practices() :HasMany
    {
        return $this->hasMany(Practice::class);
    }
}
