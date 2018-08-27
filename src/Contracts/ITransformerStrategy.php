<?php

namespace Balaremember\LaravelCommentsService\Contracts;

use Illuminate\Support\Collection;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;

interface ITransformerStrategy
{
    /**
     * @param Collection   $comments
     * @param integer      $levelDepth
     * @param integer      $pageNumber
     * @param integer|null $currentParentId
     * @return CommentsCollection
     */
    public function make(Collection $comments, int $levelDepth = null, int $pageNumber = 1, int $currentParentId = null): CommentsCollection;
}
