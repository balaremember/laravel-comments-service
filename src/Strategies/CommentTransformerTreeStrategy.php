<?php

namespace Balaremember\LaravelCommentsService\Strategies;

use Balaremember\LaravelCommentsService\Contracts\ITreeStrategy;
use Illuminate\Support\Collection;
use Balaremember\LaravelCommentsService\Transformers\CommentTransformer;
use Balaremember\LaravelCommentsService\Services\CommentService;
use Balaremember\LaravelCommentsService\Contracts\ITransformerStrategy;
use Balaremember\LaravelCommentsService\Collections\CommentsCollection;
use Balaremember\LaravelCommentsService\Comments\LazyComment;
use Balaremember\LaravelCommentsService\Comments\Comment;

class CommentTransformerTreeStrategy implements ITransformerStrategy, ITreeStrategy
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
     * @param integer|null $maxLevelDepth
     * @param integer      $pageNumber
     * @param integer      $currentParentId
     * @return CommentsCollection
     */
    public function make(Collection $commentModels, int $maxLevelDepth = null, int $pageNumber = 1, int $currentParentId = null): CommentsCollection
    {
        $i = 0;
        return $this->toTree($commentModels, $pageNumber, $maxLevelDepth, $i, $currentParentId);
    }

    /**
     * @param Collection $commentModels
     * @param integer    $pageNumber
     * @param integer    $maxLevelDepth
     * @param integer    $i
     * @param integer    $currentParentId
     * @return CommentsCollection
     */
    private function toTree(Collection $commentModels, int $pageNumber, int $maxLevelDepth, int &$i, int $currentParentId = null): CommentsCollection
    {
        $resultCollection = new CommentsCollection([], $this->service, $pageNumber);
        foreach ($commentModels as $currentCommentModel) {
            if ($currentCommentModel->parent_id == $currentParentId) {
                $i++;
                $commentEntity = (new CommentTransformer(($i == $maxLevelDepth)
                    ? new LazyComment($this->service)
                    : new Comment($this->service)))->transform($currentCommentModel);
                $branchCollection = $this->toTree($commentModels, $pageNumber, $maxLevelDepth, $i, $commentEntity->getId());
                $commentEntity->setChildren($branchCollection);
                $resultCollection->push($commentEntity);
            }
        }
        return $resultCollection;
    }
}
