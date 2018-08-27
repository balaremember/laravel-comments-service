<?php

namespace Balaremember\LaravelCommentsService\Contracts;

use Balaremember\LaravelCommentsService\Entities\Comment;

interface ITransformer
{
    /**
     * @param Comment $commentModel
     * @return IComment
     */
    public function transform(Comment $commentModel): IComment;
}
