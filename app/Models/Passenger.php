<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Passenger extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const IMAGE = 'image';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'birth_date',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::IMAGE)
            ->singleFile();
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tripClients()
    {
        return $this->morphMany(TripClient::class, 'client');
    }

    public function reviewsGiven()
    {
        return $this->morphMany(TripReview::class, 'reviewer');
    }

    public function reviewsReceived()
    {
        return $this->morphMany(TripReview::class, 'reviewee');
    }

    public function trips()
    {
        // Get trips where passenger is a trip client
        $tripsAsClient = Trip::whereHas('clients', function ($query) {
            $query->where('client_id', $this->id)
                  ->where('client_type', self::class);
        });

        // Get trips where passenger has a cargo
        $tripsWithCargo = Trip::whereHas('cargos.cargo', function ($query) {
            $query->where('passenger_id', $this->id);
        });

        // Union both queries and return distinct trips
        return $tripsAsClient->union($tripsWithCargo)->distinct();
    }

    public function cargos(){
        return $this->hasMany(Cargo::class);
    }

    public function lostAndFounds()
    {
        return $this->hasMany(LostAndFound::class);
    }

    public function getFullnameAttribute()
    {
        if ($this->first_name || $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }

        return $this->user->username;

    }

    public function getPhoneAttribute()
    {
        return $this->user->phone;
    }

    public function getAvatarUrlAttribute(){
        $image  = $this->getFirstMediaUrl('image');
        return empty($image) ? asset('assets/img/avatars/1.png') : $image;
    }

    public function adminActions()
    {
        return $this->morphMany(AdminAction::class, 'target');
    }
}
