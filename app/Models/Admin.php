<?php

namespace App\Models;


use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements HasMedia
{
  use HasFactory, Notifiable, HasRoles, InteractsWithMedia;

  const IMAGE = 'image';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'firstname',
    'lastname',
    'email',
    'phone',
    'password',
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

      public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::IMAGE)
      ->singleFile();
  }

      public function getFullnameAttribute(){
        return "{$this->firstname} {$this->lastname}";
    }

    public function getAvatarUrlAttribute(){
        $image  = $this->getFirstMediaUrl('image');
        return empty($image) ? asset('assets/img/avatars/1.png') : $image;
    }

    // Relationships
    
    /**
     * Get all actions performed by this admin
     */
    public function actions()
    {
        return $this->hasMany(AdminAction::class);
    }

}
