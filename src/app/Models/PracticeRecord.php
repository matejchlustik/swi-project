<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PracticeRecord extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [
        'id'
    ];

    public function practice() :BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }
}
