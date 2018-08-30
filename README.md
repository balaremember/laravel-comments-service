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
    composer require balaremember/laravel-comments-service
    ```
3.  ### Laravel
    
    #### >= laravel5.5
    
    ServiceProvider will be attached automatically
    
    #### Other
    
    In your `config/app.php` add `Balaremember\LaravelCommentsService\CommentsServiceProvider::class` to the end of the `providers` array:
    
    ```php
    'providers' => [
        ...
        Balaremember\LaravelCommentsService\CommentsServiceProvider::class,
    ],
    ```
    
    If Lumen
    
    ```php
    $app->register(Balaremember\LaravelCommentsService\CommentsServiceProvider::class);
    ```
    
    Publish Configuration
    
    ```shell
    php artisan vendor:publish --provider "Balaremember\LaravelCommentsService\CommentsServiceProvider"
    ```
4. Now you have comments config file with parameters:
    ```php
    'userModel' => Path to your user model, (default: App\User)
    'perPage' => Number of comments on one page, (default: 10)
    'levelDepth' => Depth of nesting. (default: 3)
    ```
    Full description of the [config](https://github.com/balaremember/laravel-comments-service/blob/master/config/comments.php) variables 
   
5. Custom Polymorphic Types in your AppServiceProvider like:
   * To connect comments to your model you need:
   ```php
   class YourModel extends Model
   {
        public function comments()
        {
            return $this->morphMany(Balaremember\LaravelCommentsService\Entities\Comment::class, 'commentable');
        }
   }
   ```
   * By default, Laravel will use the fully qualified class name to store the type of the related model. 
     However, you may wish to decouple your database from your application's internal structure.
   ```php
    use Illuminate\Database\Eloquent\Relations\Relation;
    
    Relation::morphMap([
        'posts' => 'App\Post',
        'videos' => 'App\Video',
    ]);
   ```
   See more in [Laravel Documentation](https://laravel.com/docs/5.6/eloquent-relationships#polymorphic-relations)