<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentEmployee extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        "from",
        "to",
    ];

    public $timestamps = false;

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function evaluation() :HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
