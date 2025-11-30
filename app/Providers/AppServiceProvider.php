<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini
use App\Models\Notifikasi;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Bagikan notifikasi ke semua view
        View::composer('*', function ($view) {
            if (auth()->check()) { // Pastikan user sudah login
                $notificationData = Notifikasi::where('user_id', auth()->id())
                    ->with('pengajuanBeasiswa.Beasiswa', 'pengajuanBeasiswa.Status')
                    ->orderBy('created_at', 'desc')
                    ->limit(10) // Batasi jumlah notifikasi yang diambil
                    ->get();

                $view->with('notificationData', $notificationData);
            }
        });
    }
}