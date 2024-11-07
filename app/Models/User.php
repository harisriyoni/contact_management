<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
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

}
