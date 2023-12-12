<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'body',
        'user_id',
        'practice_id'
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
