<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use NrlLaravel\Labcoat\Traits\HasUUID;
use NrlLaravel\Labcoat\Traits\HasRoles;

class User extends Authenticatable
{
  use Notifiable, HasUUID, HasRoles;
  protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function eosRequests()
    {
      return $this->hasMany(EOSRequest::class, 'user_id', 'id');
    }
}
