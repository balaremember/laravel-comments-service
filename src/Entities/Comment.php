<?php

namespace Balaremember\LaravelCommentsService\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

/**
 * Class Comment.
 *
 * @property int $id
 * @property int $user_id
 * @property int $commentable_id
 * @property string $commentable_type
 * @property int $parent_id
 * @property string $body
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class Comment extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'body', 'parent_id', 'commentable_id', 'commentable_type'
    ];

    public function user()
    {
        return $this->belongsTo(Config::get('comments.userModel'));
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
