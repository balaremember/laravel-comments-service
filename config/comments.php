<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Path to your user model
    |--------------------------------------------------------------------------
    |
    | This option is intended to determine the relationship
    | between the user and his comments.
    |
    */

    'userModel' => 'App\User',

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | This option is designed to determine the number of
    | comments on one page.
    |
    */

    'perPage' => 10,

    /*
    |--------------------------------------------------------------------------
    | Depth of nesting
    |--------------------------------------------------------------------------
    |
    | This option is used to set the level of nesting.
    |
    */

    'levelDepth' => 3
];
