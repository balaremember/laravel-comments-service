<?php

namespace Balaremember\LaravelCommentsService\Strategies;

use Balaremember\LaravelCommentsService\Contracts\IComment;
use Balaremember\LaravelCommentsService\Contracts\IListStrategy;
use Balaremember\LaravelCommentsService\Contracts\ITransformer;
use Balaremember\LaravelCommentsService\Services\CommentService;
use Balaremember\LaravelCommentsService\Collections\CommentsCollection;
use Balaremember\LaravelCommentsService\Contracts\ITransformerStrategy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class CommentTransformerListStrategy implements ITransformerStrategy, IListStrategy
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
        $comment = App::make(IComment::class, [$this]);
        $transformer = App::make(ITransformer::class, [$comment]);
        $entityCommentCollection = App::make(CommentsCollection::class, [$this->service]);

        foreach ($commentModels as $currentCommentModel) {
            $entityCommentCollection->push($transformer->transform($currentCommentModel));
        }

        return $entityCommentCollection;
    }
}
