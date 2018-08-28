<?php

namespace Balaremember\LaravelCommentsService\Service;

use Balaremember\LaravelCommentsService\Contracts\IComment;
use Balaremember\LaravelCommentsService\Contracts\IListStrategy;
use Balaremember\LaravelCommentsService\Contracts\ITransformer;
use Balaremember\LaravelCommentsService\Contracts\ITreeStrategy;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Balaremember\LaravelCommentsService\Contracts\ICommentRepository;
use Balaremember\LaravelCommentsService\Comments\Comment;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Balaremember\LaravelCommentsService\Contracts\ITransformerStrategy;

class CommentService
{
    /**
     * @var ITransformerStrategy
     */
    private $strategy;

    /**
     * @var ICommentRepository
     */
    private $repository;
    /**
     * @var Container
     */
    private $container;

    /**
     * CommentService constructor.
     * @param ICommentRepository $repository
     * @param Container $container
     */
    public function __construct(ICommentRepository $repository, Container $container)
    {
        $this->repository = $repository;
        $this->container = $container;
    }

    /**
     * @param integer $id
     * @return Comment
     */
    public function find(int $id): Comment
    {
        /** @var Comment $entity */
        $comment = $this->container->make(IComment::class, [$this]);
        $transformer = $this->container->make(ITransformer::class, [$comment]);
        $entity = $this->repository->find($id, $transformer);
        return $entity;
    }

    /**
     * @param array $data
     * @return Comment
     */
    public function create(array $data): Comment
    {
        /** @var Comment $entity */
        $comment = $this->container->make(IComment::class, [$this]);
        $transformer = $this->container->make(ITransformer::class, [$comment]);
        $entity = $this->repository->create($data, $transformer);
        return $entity;
    }

    /**
     * @param array   $data
     * @param integer $id
     * @return Comment
     */
    public function update(array $data, int $id): Comment
    {
        /** @var Comment $entity */
        $comment = $this->container->make(IComment::class, [$this]);
        $transformer = $this->container->make(ITransformer::class, [$comment]);
        $entity = $this->repository->update($data, $id, $transformer);
        return $entity;
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): ?bool
    {
        $comment = $this->container->make(IComment::class, [$this]);
        $transformer = $this->container->make(ITransformer::class, [$comment]);
        try {
            $this->repository->find($id, $transformer);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        return $this->repository->delete($id);
    }

    /**
     * @param integer $documentId
     * @return CommentsCollection
     */
    public function getListComments(int $documentId): CommentsCollection
    {
        $this->strategy = $this->container->make(IListStrategy::class);
        return $this->repository->all($documentId, $this->strategy);
    }

    /**
     * @param integer $documentId
     * @param integer $pageNumber
     * @return CommentsCollection
     */
    public function getCommentsTreeByObjectId(int $documentId, int $pageNumber): CommentsCollection
    {
        $this->strategy = $this->container->make(ITreeStrategy::class);
        return $this->repository->paginateCommentsByDocumentId($documentId, $pageNumber, $this->strategy);
    }

    /**
     * @param integer $commentId
     * @param integer $pageNumber
     * @return CommentsCollection
     */
    public function getCommentsTreeByRootCommentId(int $commentId, int $pageNumber): CommentsCollection
    {
        $this->strategy = $this->container->make(ITreeStrategy::class);
        return $this->repository->paginateCommentsByRootCommentId($commentId, $pageNumber, $this->strategy);
    }
}
