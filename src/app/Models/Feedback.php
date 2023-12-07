<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
        'user_id',
        'pracitice_id'
    ];

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function practice() :BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }
}
