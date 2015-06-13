<?php namespace Slade;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\View\Engines\CompilerEngine;

class ServiceProvider extends BaseServiceProvider {

    /**
     * Bootstrap.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['events']->listen('kernel.handled',
            function (Request $req, Response $res) {
                if ($this->app['config']->get('app.debug') &&
                    $res->headers->has('Content-Type') &&
                    strpos($res->headers->get('Content-Type'), 'html') !== false)
                {
                    $res->setContent(Template::tidy($res->getContent()));
                }
            }
        );
    }

    /**
     * Register the templating engine.
     *
     * @return void
     */
    public function register()
    {
        $resolver = $this->app['view.engine.resolver'];

        $this->registerSladeEngine($resolver);

        $this->app['view']->addExtension('slade.php', 'slade');
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerSladeEngine($resolver)
    {
        $app = $this->app;

        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Slade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $app->singleton('slade.compiler', function ($app) {
            $cache = $app['config']['view.compiled'];

            return new Compiler($app['files'], $cache);
        });

        $resolver->register('slade', function () use ($app) {
            return new CompilerEngine($app['slade.compiler'], $app['files']);
        });
    }
}