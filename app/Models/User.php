<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    use SoftDeletes;

    protected $dates = ['soft_delete'];

    const DELETED_AT = 'soft_delete';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'mail_sent',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    // ID ALIAS
    public function getIdAttribute()
    {
        return $this->user_id;
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Specify the guard name for Spatie Permissions
    protected $guard_name = 'web';
}
