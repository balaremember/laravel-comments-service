<?php

use Faker\Generator as Faker;
use Balaremember\LaravelCommentsService\Entities\Comment;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 5),
        'commentable_id' => $faker->numberBetween(1, 5),
        'commentable_type' => 'document',
        'parent_id' => $faker->numberBetween(1, 5),
        'body' => $faker->text($maxNbChars = 100)
    ];
});
