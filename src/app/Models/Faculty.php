<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'short',
    ];
    public $timestamps = false;
    public static function booted()
    {

        static::deleting(function ($faculty) {
            $faculty->department()->get()->each->delete();
        });


        static::restored(function ($faculty) {
            $faculty->department()->withTrashed()->get()->each->restore();
        });
    }

    public function department() :HasMany
    {
        return $this->hasMany(Department::class);
    }
}
