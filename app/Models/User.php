<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Model implements Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return null; // Zwracamy null, jeśli nie używamy remember_token
    }

    public function setRememberToken($value)
    {
        // Jeśli nie używamy remember_token, ta metoda może pozostać pusta
    }

    public function getRememberTokenName()
    {
        return null; // Zwracamy null, jeśli nie używamy remember_token
    }




    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
