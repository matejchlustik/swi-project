<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Major extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'name',
        'department_id'
    ];

    public $timestamps = false;
    public static function booted()
    {
        static::deleting(function ($major) {
            $major->program()->get()->each->delete();
        });
        static::restored(function ($major) {
            $major->program()->withTrashed()->get()->each->restore();
        });
    }
    public function department() :BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function program() :HasMany
    {
        return $this->hasMany(Program::class);
    }
}
