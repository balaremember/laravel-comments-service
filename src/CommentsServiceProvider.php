<?php

namespace Balaremember\LaravelCommentsService;

use Balaremember\LaravelCommentsService\Collections\CommentsCollection;
use Balaremember\LaravelCommentsService\Comments\Comment;
use Balaremember\LaravelCommentsService\Comments\LazyComment;
use Balaremember\LaravelCommentsService\Contracts\IComment;
use Balaremember\LaravelCommentsService\Contracts\ICommentRepository;
use Balaremember\LaravelCommentsService\Contracts\ILazyComment;
use Balaremember\LaravelCommentsService\Contracts\IListStrategy;
use Balaremember\LaravelCommentsService\Contracts\ITransformer;
use Balaremember\LaravelCommentsService\Contracts\ITreeStrategy;
use Balaremember\LaravelCommentsService\DatabaseAbstractLayer\CommentRepositoryAdapter;
use Balaremember\LaravelCommentsService\Services\CommentService;
use Balaremember\LaravelCommentsService\Strategies\CommentTransformerListStrategy;
use Balaremember\LaravelCommentsService\Strategies\CommentTransformerTreeStrategy;
use Balaremember\LaravelCommentsService\Transformers\CommentTransformer;
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
        $this->publishes([
            __DIR__.'/../config/comments.php' => config_path('comments.php'),
        ], 'laravel-comments-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/0000_00_00_000000_create_comments_table.php');
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
