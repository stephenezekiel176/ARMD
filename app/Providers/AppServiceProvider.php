<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Department;

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
        // Ensure the "role" middleware alias is available at runtime. Some bootstrap/cache
        // or runtime configurations may not include custom kernel aliases, so register
        // the alias here as a fallback.
        if ($this->app->bound('router')) {
            $this->app['router']->aliasMiddleware('role', \App\Http\Middleware\Role::class);
        }

        // Share departments with all views for dropdown functionality
        View::composer('*', function ($view) {
            $view->with('departments', Department::orderBy('name')->get());
        });
    }
}
