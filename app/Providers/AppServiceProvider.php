<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\EloquentUserProvider;

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
        Auth::provider('cached-eloquent', function ($app, array $config) {
            return new class($app['hash'], $config['model']) extends EloquentUserProvider {
                public function retrieveById($identifier)
                {
                    return Cache::remember('user_model_' . $identifier, 30, function () use ($identifier) {
                        return parent::retrieveById($identifier);
                    });
                }

                public function retrieveByCredentials(array $credentials)
                {
                    if (empty($credentials) || (count($credentials) === 1 && array_key_exists('password', $credentials))) {
                        return null;
                    }

                    if (isset($credentials['id_pegawai'])) {
                        return Cache::remember('user_creds_' . $credentials['id_pegawai'], 30, function () use ($credentials) {
                            return parent::retrieveByCredentials($credentials);
                        });
                    }

                    if (isset($credentials['email'])) {
                        return Cache::remember('user_creds_' . $credentials['email'], 30, function () use ($credentials) {
                            return parent::retrieveByCredentials($credentials);
                        });
                    }

                    return parent::retrieveByCredentials($credentials);
                }
            };
        });
    }
}