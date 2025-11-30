<?php

// app/Providers/FirebaseServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Auth;
use App\Services\FirebaseAuthService;

class FirebaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FirebaseAuthService::class, function ($app) {
            return new FirebaseAuthService($app->make(Auth::class));
        });
    }

    public function boot()
    {
        //
    }
}

