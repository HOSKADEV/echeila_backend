<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notifications\Channels\FcmChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    $this->app->make(ChannelManager::class)->extend('fcm', function ($app) {
      return $app->make(FcmChannel::class);
    });

    // Set default pagination views
    Paginator::defaultView('pagination::bootstrap-5');
    Paginator::defaultSimpleView('pagination::simple-bootstrap-5');
  }
}
