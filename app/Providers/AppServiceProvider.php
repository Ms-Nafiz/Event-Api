<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- এই লাইনটি import করুন

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
        // --- এই কোডটুকু যোগ করুন ---
        // যদি এনভায়রনমেন্ট 'local' হয়, তবে https ব্যবহার না করার জন্য
        // if (env('APP_ENV') === 'local') {
        //     URL::forceScheme('http');
        // }
        // ---------------------------
    }
}
