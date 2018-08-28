<?php

namespace Balaremember\LaravelCommentsService;

use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Balaremember\LaravelCommentsService\Comments\Comment;
use Balaremember\LaravelCommentsService\Comments\LazyComment;
use Balaremember\LaravelCommentsService\Contracts\IComment;
use Balaremember\LaravelCommentsService\Contracts\ICommentRepository;
use Balaremember\LaravelCommentsService\Contracts\ILazyComment;
use Balaremember\LaravelCommentsService\Contracts\IListStrategy;
use Balaremember\LaravelCommentsService\Contracts\ITransformer;
use Balaremember\LaravelCommentsService\Contracts\ITreeStrategy;
use Balaremember\LaravelCommentsService\Contracts\IUserModel;
use Balaremember\LaravelCommentsService\DatabaseAbstractLayer\CommentRepositoryAdapter;
use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\Strategy\CommentTransformerListStrategy;
use Balaremember\LaravelCommentsService\Strategy\CommentTransformerTreeStrategy;
use Balaremember\LaravelCommentsService\Transformer\CommentTransformer;
use Illuminate\Support\Facades\App;
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
        $this->app->bind(ICommentRepository::class, CommentRepositoryAdapter::class);
        $this->mergeConfigFrom(
            __DIR__.'/../config/comments.php',
            'comments'
        );
        $this->app->bind(ITransformer::class, CommentTransformer::class);
        $this->app->bind(IListStrategy::class, CommentTransformerListStrategy::class);
        $this->app->bind(ITreeStrategy::class, CommentTransformerTreeStrategy::class);
        $this->app->bind(ILazyComment::class, LazyComment::class);
        $this->app->bind(IComment::class, Comment::class);
        $this->app->bind(CommentsCollection::class, function ($app) {
            return new CommentsCollection([], App::make(CommentService::class));
        });
    }
}
