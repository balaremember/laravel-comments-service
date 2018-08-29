<?php

namespace Balaremember\LaravelCommentsService\DatabaseAbstractLayer;

use Balaremember\LaravelCommentsService\Entities\Comment;

/**
 * Class CommentRepositoryAdapter.
 */
class CommentRepository extends BaseRepository
{
    /**
     * @return Comment
     */
    public function getInstance()
    {
        return new Comment();
    }
}
