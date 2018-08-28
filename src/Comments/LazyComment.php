<?php

namespace Balaremember\LaravelCommentsService\Comments;

use Balaremember\LaravelCommentsService\Contracts\ILazyComment;
use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;

class LazyComment extends Comment implements ILazyComment
{
    /**
     * @var boolean
     */
    private $isLoaded = false;

    /**
     * LazyComment constructor.
     * @param CommentService $service
     * @param integer        $pageNumber
     */
    public function __construct(CommentService $service, int $pageNumber = 1)
    {
        parent::__construct($service, $pageNumber);
    }

    /**
     * Get comments from tree
     * @return CommentsCollection
     */
    public function getComments(): CommentsCollection
    {
        if (!$this->isLoaded) {
            parent::setChildren($this->service->getCommentsTreeByRootCommentId($this->getId(), 1));
            $this->isLoaded = true;
        }
        return parent::getComments();
    }
}
