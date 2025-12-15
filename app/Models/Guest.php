<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'phone',
    ];

    public function tripClients()
    {
        return $this->morphMany(TripClient::class, 'client');
    }

    public function getAvatarUrlAttribute(){
        return asset('assets/img/avatars/1.png');
    }
}