<?php

namespace Balaremember\LaravelCommentsService\Service;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Balaremember\LaravelCommentsService\Contracts\ICommentRepository;
use Balaremember\LaravelCommentsService\Transformer\CommentTransformer;
use Balaremember\LaravelCommentsService\Comments\Comment;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Balaremember\LaravelCommentsService\Strategy\CommentTransformerTreeStrategy;
use Balaremember\LaravelCommentsService\Strategy\CommentTransformerListStrategy;
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
     * CommentService constructor.
     * @param ICommentRepository $repository
     */
    public function __construct(ICommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param integer $id
     * @return Comment
     */
    public function find(int $id): Comment
    {
        /** @var Comment $entity */
        $transformer = new CommentTransformer(new Comment($this));
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
        $transformer = new CommentTransformer(new Comment($this));
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
        $transformer = new CommentTransformer(new Comment($this));
        $entity = $this->repository->update($data, $id, $transformer);
        return $entity;
    }

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): ?bool
    {
        $transformer = new CommentTransformer(new Comment($this));
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
        $this->strategy = new CommentTransformerListStrategy($this);
        return $this->repository->all($documentId, $this->strategy);
    }

    /**
     * @param integer $documentId
     * @param integer $pageNumber
     * @return CommentsCollection
     */
    public function getCommentsTreeByDocumentId(int $documentId, int $pageNumber): CommentsCollection
    {
        $this->strategy = new CommentTransformerTreeStrategy($this);
        return $this->repository->paginateCommentsByDocumentId($documentId, $pageNumber, $this->strategy);
    }

    /**
     * @param integer $commentId
     * @param integer $pageNumber
     * @return CommentsCollection
     */
    public function getCommentsTreeByRootCommentId(int $commentId, int $pageNumber): CommentsCollection
    {
        $this->strategy = new CommentTransformerTreeStrategy($this);
        return $this->repository->paginateCommentsByRootCommentId($commentId, $pageNumber, $this->strategy);
    }
}
