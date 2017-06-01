<?php

namespace Bharat\LaravelGGWallet;


use Illuminate\Support\ServiceProvider;


class GGWalletServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Bharat\LaravelGGWallet\Contracts\Factory', function ($app) {
            return new GGWalletManager($app);
        });
    }


    public function boot(){
        $this->loadViewsFrom(__DIR__.'/resources/views', 'ggwallet');
    }



    public function provides(){
        return ['Bharat\LaravelGGWallet\Contracts\Factory'];
    }
}