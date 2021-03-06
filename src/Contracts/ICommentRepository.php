<?php

namespace Balaremember\LaravelCommentsService\Contracts;

use Balaremember\LaravelCommentsService\Collections\CommentsCollection;
use Balaremember\LaravelCommentsService\Transformers\CommentTransformer;
use Balaremember\LaravelCommentsService\Comments\Comment;
/**
 * Interface ICommentRepository.
 */
interface ICommentRepository
{
    /**
     * @param integer              $documentId
     * @param ITransformerStrategy $strategy
     * @return CommentsCollection
     */
    public function all(int $documentId, ITransformerStrategy $strategy): CommentsCollection;

    /**
     * @param integer            $id
     * @param CommentTransformer $transformer
     * @return mixed
     */
    public function find(int $id, CommentTransformer $transformer): Comment;

    /**
     * @param array              $data
     * @param CommentTransformer $transformer
     * @return mixed
     */
    public function create(array $data, CommentTransformer $transformer): Comment;

    /**
     * @param array              $data
     * @param integer            $id
     * @param CommentTransformer $transformer
     * @return mixed
     */
    public function update(array $data, int $id, CommentTransformer $transformer): Comment;

    /**
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool ;

    /**
     * @param integer              $objectId
     * @param integer              $pageNumber
     * @param string               $type
     * @param ITransformerStrategy $strategy
     * @return mixed
     */
    public function paginateCommentsByObjectId(int $objectId, int $pageNumber, string $type, ITransformerStrategy $strategy): CommentsCollection;

    /**
     * @param integer              $commentId
     * @param integer              $pageNumber
     * @param ITransformerStrategy $strategy
     * @return mixed
     */
    public function paginateCommentsByRootCommentId(int $commentId, int $pageNumber, ITransformerStrategy $strategy): CommentsCollection;
}
