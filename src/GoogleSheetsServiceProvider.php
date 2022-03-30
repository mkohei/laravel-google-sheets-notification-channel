<?php

namespace Mkohei\LaravelGoogleSheetsNotificationChannel;

use Google\Service\Sheets;
use Illuminate\Support\ServiceProvider;

class GoogleSheetsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(GoogleSheetsChannel::class)
            ->needs(\Google\Service\Sheets::class)
            ->give(function () {
                return new Sheets([
                    'client_id' => config('services.google.sheets.client_id'),
                    'client_secret' => config('services.google.sheets.client_secret'),
                ]);
            });
    }
}
