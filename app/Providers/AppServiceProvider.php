<?php

namespace App\Providers;

use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Gate::define('admin', function ($user) {
            return $user instanceof User && true;
        });

        Arr::macro('assocToOptions', function ($src) {
            $options = [];
            foreach ($src as $id => $name) {
                $options[] = ['id' => $id, 'name' => $name];
            }
            return $options;
        });

    }
}
