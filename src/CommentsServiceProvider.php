<?php

namespace Balaremember\LaravelCommentsService;

use Illuminate\Support\ServiceProvider;

class CommentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // php artisan vendor:publish -tag=laravel-comments-config
        $this->publishes([
            __DIR__.'/../config/comments.php' => config_path('comments.php'),
        ], 'laravel-comments-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations/2018_07_20_043605_create_comments_table.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/comments.php',
            'comments'
        );
    }
}
