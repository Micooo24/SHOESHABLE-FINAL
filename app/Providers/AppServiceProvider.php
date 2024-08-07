<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Meilisearch\Client as MeiliClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

     public function register(): void
     {
         // Alias for Debugbar
         $loader = \Illuminate\Foundation\AliasLoader::getInstance();
         $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);

         $this->app->singleton(MeiliClient::class, function ($app) {
            $config = $app['config']['services.meilisearch'];
            if (empty($config['host'])) {
                throw new \InvalidArgumentException('Meilisearch host not configured.');
            }
            $key = $config['key'] ?? null;
            return new MeiliClient($config['host'], $key);
        });
     }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
