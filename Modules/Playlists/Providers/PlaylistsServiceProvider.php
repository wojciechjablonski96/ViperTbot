<?php

namespace Modules\Playlists\Providers;

use Alaouy\Youtube\Youtube;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Playlists\Contracts\PlaylistsInterface;
use Modules\Playlists\Contracts\RequestedSongsInterface;
use Modules\Playlists\Contracts\SongsInterface;
use Modules\Playlists\Repositories\PlaylistsRepository;
use Modules\Playlists\Repositories\RequestedSongsRepository;
use Modules\Playlists\Repositories\SongsRepository;

class PlaylistsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            PlaylistsInterface::class,
            PlaylistsRepository::class
        );

        $this->app->singleton(
            SongsInterface::class,
            SongsRepository::class
        );

        $this->app->singleton(
            RequestedSongsInterface::class,
            RequestedSongsRepository::class
        );


        $this->app->when(Youtube::class)
            ->needs('$key')
            ->give(config('youtube.key'));
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('playlists.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'playlists'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/playlists');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/playlists';
        }, \Config::get('view.paths')), [$sourcePath]), 'playlists');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/playlists');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'playlists');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'playlists');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
