<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

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
        // Register Blade directive for signed admin URLs
        // Usage: @signedRoute('admin.profile') or @signedRoute('admin.bookings.review', ['id' => 1])
        Blade::directive('signedRoute', function ($expression) {
            return "<?php echo \\Illuminate\\Support\\Facades\\URL::signedRoute($expression); ?>";
        });

        // Register global helper function
        if (!function_exists('signed_route')) {
            function signed_route(string $name, array $parameters = []): string
            {
                return URL::signedRoute($name, $parameters);
            }
        }
    }
}
