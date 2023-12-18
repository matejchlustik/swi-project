<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\DepartmentEmployee;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'from',
        'to'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public static function booted()
    {
        static::deleting(function ($user) {
            $user->departmentEmployee()->delete();
            $user->companyEmployee()->delete();
        });
        static::restored(function ($user) {
            $user->departmentEmployee()->withTrashed()->restore();
            $user->companyEmployee()->withTrashed()->restore();
        });
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    public function role() :BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function practices() :HasMany
    {
        return $this->hasMany(Practice::class);
    }

    public function companyEmployee() :HasOne
    {
        return $this->hasOne(CompanyEmployee::class);
    }

    public function departmentEmployee() :HasMany
    {
        return $this->hasMany(DepartmentEmployee::class);
    }
    public function comment() :HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function feedback() :HasMany
    {
        return $this->hasMany(feedback::class);
    }

}
