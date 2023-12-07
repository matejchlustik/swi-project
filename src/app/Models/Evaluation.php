<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Evaluation extends Model
{
    public function departmentEmployee() :BelongsTo
    {
        return $this->belongsTo(DepartmentEmployee::class);
    }

    public function practice() :BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }
}
