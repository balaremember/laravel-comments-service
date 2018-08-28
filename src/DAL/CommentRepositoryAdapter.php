<?php

namespace Balaremember\LaravelCommentsService\DatabaseAbstractLayer;

use Balaremember\LaravelCommentsService\Contracts\ICommentRepository;
use Balaremember\LaravelCommentsService\Contracts\ITransformerStrategy;
use Balaremember\LaravelCommentsService\Transformer\CommentTransformer;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Balaremember\LaravelCommentsService\Comments\Comment;

/**
 * Class CommentRepositoryAdapter.
 */
class CommentRepositoryAdapter implements ICommentRepository
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * CommentRepositoryAdapter constructor.
     * @param Repository        $config
     * @param Container         $container
     * @param CommentRepository $repository
     */
    public function __construct(Repository $config, Container $container, CommentRepository $repository)
    {
        $this->container = $container;
        $this->config = $config;
        $this->repository = $repository;
    }

    /**
     * @param integer              $documentId
     * @param ITransformerStrategy $strategy
     * @return CommentsCollection
     */
    public function all(int $documentId, ITransformerStrategy $strategy): CommentsCollection
    {
        $commentModels = $this->repository->all();
        return $strategy->make($commentModels);
    }

    /**
     * @param integer            $id
     * @param CommentTransformer $transformer
     * @return mixed
     */
    public function find(int $id, CommentTransformer $transformer): Comment
    {
        $model = $this->repository->find($id);
        return $transformer->transform($model);
    }

    /**
     * @param array              $data
     * @param CommentTransformer $transformer
     * @return mixed
     */
    public function create(array $data, CommentTransformer $transformer): Comment
    {
        $model = $this->repository->create($data);
        return $transformer->transform($model);
    }

    /**
     * @param array              $data
     * @param integer            $id
     * @param CommentTransformer $transformer
     * @return mixed
     */
    public function update(array $data, int $id, CommentTransformer $transformer): Comment
    {
        $model = $this->repository->update($data, $id);
        return $transformer->transform($model);
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * @param int $objectId
     * @param integer $pageNumber
     * @param string $type
     * @param ITransformerStrategy $strategy
     * @return mixed
     */
    public function paginateCommentsByObjectId(int $objectId, int $pageNumber, string $type, ITransformerStrategy $strategy): CommentsCollection
    {
        $perPage = $this->config->get('comments.perPage');
        $maxLevelDepth = ($this->config->get('comments.levelDepth') - 1);
        $comments = $this->container->make(Collection::class);
        $rootLevelComments = $this->repository->getInstance()
            ->whereNull('parent_id')
            ->where([
                'commentable_type' => $type,
                'commentable_id' => $objectId
            ])
            ->skip(($perPage*$pageNumber) - $perPage)
            ->take($perPage)
            ->get();

        $rootLevelCommentIds = $rootLevelComments->pluck('id');
        $comments = $comments->merge($rootLevelComments)->merge($this->paginateChildrenByRootCommentId($pageNumber, $rootLevelCommentIds, $maxLevelDepth));
        return $strategy->make($comments, $maxLevelDepth);
    }

    /**
     * @param integer              $rootCommentId
     * @param integer              $pageNumber
     * @param ITransformerStrategy $strategy
     * @return mixed
     */
    public function paginateCommentsByRootCommentId(int $rootCommentId, int $pageNumber, ITransformerStrategy $strategy): CommentsCollection
    {
        $maxLevelDepth = $this->config->get('comments.levelDepth');
        $rootCommentIdCollection = $this->container->make(Collection::class);
        $rootCommentIdCollection->push($rootCommentId);
        $comments = $this->paginateChildrenByRootCommentId($pageNumber, $rootCommentIdCollection, $maxLevelDepth);
        return $strategy->make($comments, $maxLevelDepth, $pageNumber, $rootCommentId);
    }

    /**
     * @param integer    $pageNumber
     * @param Collection $rootLevelCommentsIds
     * @param integer    $levelDepth
     * @return Collection
     */
    private function paginateChildrenByRootCommentId(int $pageNumber, Collection $rootLevelCommentsIds, int $levelDepth): Collection
    {
        $perPage = $this->config->get('comments.perPage');
        $comments = $this->container->make(Collection::class);
        for ($i = 0; $i < $levelDepth; $i++) {
            $currentChildLevelComments = $this->repository->getInstance()
                ->whereIn('parent_id', $rootLevelCommentsIds)
                ->skip(($perPage*$pageNumber) - $perPage)
                ->take($perPage)
                ->get();

            $rootLevelCommentsIds = $currentChildLevelComments->pluck('id');
            $comments = $comments->merge($currentChildLevelComments);
        }

        return $comments;
    }
}
