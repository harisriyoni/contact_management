<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements Authenticatable
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'username', 'name', 'password',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'users_id', 'id');
    }
    public function getAuthIdentifierName()
    {
        return 'username';
    }
    public function getAuthIdentifier()
    {
        return $this->username;
    }
    public function getAuthPassword()
    {
        return $this->password;
    }
    public function getRememberToken()
    {
        return $this->token;
    }
    public function setRememberToken($value)
    {
        $this->token = $value;
    }
    public function getRememberTokenName()
    {
        return 'token';
    }

}
