<?php namespace Slade;

use Illuminate\Support\ServiceProvider;

class SladeServiceProvider extends ServiceProvider {

    /**
     * Bootstrap.
     *
     * @return void
     */
    public function boot()
    {
        Parser::$templatePaths = $this->app['config']['view']['paths'];
    }

    /**
     * Register any services.
     *
     * @return void
     */
    public function register()
    {
        
    }
    
}