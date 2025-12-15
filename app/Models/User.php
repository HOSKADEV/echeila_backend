<?php

namespace App\Models;

use App\Constants\UserType;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'username',
    'phone',
    'password',
    'status',
    'device_token'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
    'device_token'
  ];

  // Relationships
  public function passenger()
  {
    return $this->hasOne(Passenger::class);
  }

  public function federation()
  {
    return $this->hasOne(Federation::class);
  }

  public function driver()
  {
    return $this->hasOne(Driver::class);
  }

  public function wallet()
  {
    return $this->hasOne(Wallet::class);
  }

  public function getTypeAttribute(){
    if($this->has('federation')){
      return UserType::FEDERATION;
    }

    if($this->has('driver')){
      return UserType::DRIVER;
    }

    return UserType::PASSENGER;
  }

  /**
   * Scope a query to users of a given type.
   * Usage: User::type(UserType::DRIVER)->get();
   */
  public function scopeType($query, string $type)
  {
    switch ($type) {
      case UserType::FEDERATION:
        return $query->whereHas('federation')->with('federation', 'passenger');
      case UserType::DRIVER:
        return $query->whereHas('driver')->with('driver', 'passenger');
      case UserType::PASSENGER:
        return $query->whereHas('passenger')->with('passenger');
      default:
        return $query;
    }
  }

  public function scopePassengers($query)
  {
    return $query->type(UserType::PASSENGER);
  }

  public function scopeDrivers($query)
  {
    return $query->type(UserType::DRIVER);
  }

  public function scopeFederations($query)
  {
    return $query->type(UserType::FEDERATION);
  }

}
