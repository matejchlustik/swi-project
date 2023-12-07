<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartmentEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function evaluation() :HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
