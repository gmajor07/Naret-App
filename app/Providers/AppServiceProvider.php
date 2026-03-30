<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Correct public path for shared hosting
        $this->app->bind('path.public', function () {
            return realpath(base_path('../public_html/naret-app'));
        });
        

        
    }
    
 

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
