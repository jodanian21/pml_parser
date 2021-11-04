<?php

namespace Jodan\PMLParser;

use Illuminate\Support\ServiceProvider;

class PMLParserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(PMLParser::class, function() {
            return new PMLParser();
        });
    }
}
