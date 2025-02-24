<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory;
    public function usereable()
    {
        return $this->morphTo(__FUNCTION__, 'usereable_type', 'usereable_id');
    }
    public function ville(){
        return $this->belongsTo(Ville::class);
    }
}
