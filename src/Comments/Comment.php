<?php

namespace Balaremember\LaravelCommentsService\Comments;

use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Balaremember\LaravelCommentsService\Contracts\IComment;

class Comment implements IComment
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $commentableId;

    /**
     * @var string
     */
    private $commentableType;

    /**
     * @var int|null
     */
    private $parentId;

    /**
     * @var string
     */
    private $message;

    /**
     * @var integer
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $updatedAt;

    /**
     * @var integer
     */
    private $deletedAt;

    /**
     * @var CommentsCollection
     */
    private $children;

    /**
     * @var CommentService
     */
    protected $service;

    /**
     * Comment constructor.
     * @param CommentService $service
     * @param integer        $pageNumber
     */
    public function __construct(CommentService $service, int $pageNumber = 1)
    {
        $this->service = $service;
        $this->children = new CommentsCollection([], $service, $pageNumber);
    }

    /**
     * Set comment id
     * @param integer $id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get comment id
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set comment user id
     * @param integer $userId
     * @return void
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Get comment user id
     * @return integer
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set comment commentable id
     * @param integer $commentableId
     * @return void
     */
    public function setEntityId(int $commentableId): void
    {
        $this->commentableId = $commentableId;
    }

    /**
     * Get comment commentable id
     * @return integer
     */
    public function getEntityId(): int
    {
        return $this->commentableId;
    }

    /**
     * Set comment commentable type
     * @param string $commentableType
     * @return void
     */
    public function setEntityType(string $commentableType): void
    {
        $this->commentableType = $commentableType;
    }

    /**
     * Get comment commentable type
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->commentableType;
    }

    /**
     * Set comment parent id
     * @param integer|null $parentId
     * @return void
     */
    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * Get comment parent id
     * @return integer
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Set comment message
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Get comment message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set created time
     * @param integer $createdAt
     * @return void
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get created time
     * @return integer
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * Set updated time
     * @param integer $updatedAt
     * @return void
     */
    public function setUpdatedAt(int $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updated time
     * @return integer
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * Set deleted time
     * @param integer $deletedAt
     * @return void
     */
    public function setDeletedAt(?int $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * Get deleted time
     * @return integer
     */
    public function getDeletedAt(): ?int
    {
        return $this->deletedAt;
    }

    /**
     * Set comment children
     * @param CommentsCollection $children
     * @return void
     */
    public function setChildren(CommentsCollection $children): void
    {
        $this->children = $children;
    }

    /**
     * Add comment to tree
     * @param IComment $comment
     * @return void
     */
    public function addComment(IComment $comment): void
    {
        $this->children->push($comment);
    }

    /**
     * Remove comment from tree
     * @param integer $id
     * @return void
     */
    public function removeCommentById(int $id): void
    {
        $this->children->where('id', $id)->forget($id);
    }

    /**
     * Get comments from tree
     * @return CommentsCollection
     */
    public function getComments(): CommentsCollection
    {
        return $this->children;
    }

    /**
     * @return integer
     */
    public function getChildCount(): int
    {
        return $this->children->count();
    }
}
