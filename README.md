# Laravel comments
Tree comments system for laravel projects.

## Installation
1. Add the next git repository definition into your composer.json file:
    ```
    "repositories": [
        ...
        {
            "type": "vcs",
            "url": "https://github.com/balaremember/laravel-comments-service"
        }
        ...
    ]
    ```
2. Run composer install:
    ```
    composer require balaremember/laravel-comments-service --dev
    ```
3. Publish vendor definitions:
    ```
    php artisan vendor:publish --provider="Balaremember\LaravelCommentsService\CommentsServiceProvider"
    ```
4. Now you have comments config file with parameters:
    ```
    'perPage' => Number of comments on one level,
    'levelDepth' => Depth of comments for the original download
    ```
   
5. Custom Polymorphic Types in your AppServiceProvider like:
    ```
    use Illuminate\Database\Eloquent\Relations\Relation;
    
    Relation::morphMap([
        'posts' => 'App\Post',
        'videos' => 'App\Video',
    ]);
    ```
    See more in [Laravel Documentation](https://laravel.com/docs/5.6/eloquent-relationships#polymorphic-relations)