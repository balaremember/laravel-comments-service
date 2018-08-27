<?php

namespace Balaremember\LaravelCommentsService\Transformer;

use Balaremember\LaravelCommentsService\Contracts\ITransformer;
use Balaremember\LaravelCommentsService\Contracts\IComment;
use Balaremember\LaravelCommentsService\Entities\Comment;

class CommentTransformer implements ITransformer
{
    /**
     * @var IComment
     */
    private $commentEntity;

    /**
     * CommentTransformer constructor.
     * @param IComment $commentEntity
     */
    public function __construct(IComment $commentEntity)
    {
        $this->commentEntity = $commentEntity;
    }

    /**
     * @param Comment $commentModel
     * @return IComment
     */
    public function transform(Comment $commentModel): IComment
    {
        $this->commentEntity->setId($commentModel->id);
        $this->commentEntity->setUserId($commentModel->user_id);
        $this->commentEntity->setEntityId($commentModel->commentable_id);
        $this->commentEntity->setEntityType($commentModel->commentable_type);
        $this->commentEntity->setParentId($commentModel->parent_id);
        $this->commentEntity->setMessage($commentModel->body);
        $this->commentEntity->setCreatedAt($commentModel->created_at->timestamp);
        $this->commentEntity->setUpdatedAt($commentModel->updated_at->timestamp);
        $this->commentEntity->setDeletedAt(data_get($commentModel, 'deleted_at.timestamp', null));
        return $this->commentEntity;
    }
}
