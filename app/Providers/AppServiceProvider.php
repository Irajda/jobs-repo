<?php

namespace App\Providers;

use Illuminate\Routing\PendingResourceRegistration;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro('setPermission', function (string $name) {
            $this->setAction(array_merge($this->getAction(), ['permission' => $name]));
            return $this;
        });

        Route::macro('getPermission', function () {
            return array_key_exists('permission', $this->getAction()) ? $this->getAction()['permission'] : null;
        });

        PendingResourceRegistration::macro('setPermissions', function (array $names) {
            $this->options['permissions'] = $names;
            return $this;
        });
    }
}
