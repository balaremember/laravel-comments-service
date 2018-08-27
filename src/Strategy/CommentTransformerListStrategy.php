<?php

namespace Balaremember\LaravelCommentsService\Strategy;

use Balaremember\LaravelCommentsService\Comments\Comment;
use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\Transformer\CommentTransformer;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Balaremember\LaravelCommentsService\Contracts\ITransformerStrategy;
use Illuminate\Support\Collection;

class CommentTransformerListStrategy implements ITransformerStrategy
{
    /**
     * @var CommentService
     */
    private $service;

    /**
     * CommentTransformerTreeStrategy constructor.
     * @param CommentService $service
     */
    public function __construct(CommentService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Collection   $commentModels
     * @param integer      $levelDepth
     * @param integer      $pageNumber
     * @param integer|null $currentParentId
     * @return CommentsCollection
     */
    public function make(Collection $commentModels, int $levelDepth = null, int $pageNumber = 1, int $currentParentId = null): CommentsCollection
    {
        $transformer = new CommentTransformer(new Comment($this->service));
        $entityCommentCollection = new CommentsCollection([], $this->service);

        foreach ($commentModels as $currentCommentModel) {
            $entityCommentCollection->push($transformer->transform($currentCommentModel));
        }

        return $entityCommentCollection;
    }
}
