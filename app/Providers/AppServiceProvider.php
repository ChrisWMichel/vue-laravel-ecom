<?php

namespace App\Providers;

use Aws\Laravel\AwsServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

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
       // Vite::prefetch(concurrency: 3);
        // Configure AWS SDK to use Laravel's logging
        $this->app->singleton('aws', function($app) {
            $config = $app->make('config')->get('services.aws');
            
            // Add logging configuration
            $config['debug'] = true;
            $config['http'] = [
                'debug' => true
            ];
            
            return new \Aws\Sdk($config);
        });
    }
}
