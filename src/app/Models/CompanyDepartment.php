<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDepartment extends Model
{
    protected $table = 'company_department';


    public function practiceOffers()
    {
        return $this->hasMany(PracticeOffers::class);
    }
}
