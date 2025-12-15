<?php

namespace App\Models;

use App\Constants\DriverStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const IMAGE = 'image';

    protected $fillable = [
        'user_id',
        'federation_id',
        'first_name',
        'last_name',
        'birth_date',
        'email',
        'status',
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

    public function federation()
    {
        return $this->belongsTo(Federation::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'entity');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)
        ->where('end_date', '>=', now())->latestOfMany();
    }

    public function reviewsGiven()
    {
        return $this->morphMany(TripReview::class, 'reviewer');
    }

    public function reviewsReceived()
    {
        return $this->morphMany(TripReview::class, 'reviewee');
    }

    public function getTripCountAttribute()
    {
        return $this->trips()->count();
    }

    public function getReviewAverageAttribute()
    {
        return $this->reviewsReceived()->avg('rating') ?? 0;
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


    public function income($period = 'month')
    {
        $query = $this->trips();

        // Filter by period
        match ($period) {
            'day' => $query->whereDate('created_at', now()->toDateString()),
            'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'year' => $query->whereYear('created_at', now()->year),
            default => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
        };

        $trips = $query->with('clients', 'cargos')->get();

        // Calculate income from trip clients (passengers)
        $clientsIncome = $trips->flatMap(function ($trip) {
            return $trip->clients;
        })->sum('total_fees');

        // Calculate income from trip cargos
        $cargosIncome = $trips->flatMap(function ($trip) {
            return $trip->cargos;
        })->sum('total_fees');

        // Total income = trips clients total fees + trips cargos total fees
        return $clientsIncome + $cargosIncome;
    }
}