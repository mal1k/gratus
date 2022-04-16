<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\URL;
use ConsoleTVs\Charts\Registrar as Charts;

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
    public function boot(Charts $charts)
    {

        $charts->register([
            \App\Charts\ReceiverChart::class,
            \App\Charts\TippersChart::class,
            \App\Charts\RevenueChart::class
        ]);

        /**
         * @throw Illuminate\Database\LazyLoadingViolationException
         * Throw it in case the lazy loading of a model is presented
         */
        // Model::preventLazyLoading(! $this->app->isProduction());
        Model::preventLazyLoading(true);

        /**
         * The global default validation settings for passwords
         */
        Password::defaults(function () {
            $rule = Password::min(10);

            return $this->app->isProduction() ?
                    $rule->mixedCase()->numbers()->symbols()->uncompromised() : $rule;
        });

        /**
         * Force the scheme of all urls within the app
         */
        if (env('APP_FORCED_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}
