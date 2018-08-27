<?php

namespace Balaremember\LaravelCommentsService\Contracts;

use Balaremember\LaravelCommentsService\Collection\CommentsCollection;

interface IComment
{
    /**
     * @param integer $id
     * @return void
     */
    public function setId(int $id): void;

    /**
     * @return integer
     */
    public function getId(): int;

    /**
     * @param integer $userId
     * @return void
     */
    public function setUserId(int $userId): void;

    /**
     * @return integer
     */
    public function getUserId(): int;

    /**
     * @param integer $entityId
     * @return void
     */
    public function setEntityId(int $entityId): void;

    /**
     * @return integer
     */
    public function getEntityId(): int;

    /**
     * @param string $entityType
     * @return void
     */
    public function setEntityType(string $entityType): void;

    /**
     * @return string
     */
    public function getEntityType(): string;

    /**
     * @param integer|null $parentId
     * @return void
     */
    public function setParentId(?int $parentId): void;

    /**
     * @return integer|null
     */
    public function getParentId(): ?int;

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param integer $createdAt
     * @return void
     */
    public function setCreatedAt(int $createdAt): void;

    /**
     * @return integer
     */
    public function getCreatedAt(): int;

    /**
     * @param integer $updatedAt
     * @return void
     */
    public function setUpdatedAt(int $updatedAt): void;

    /**
     * @return integer
     */
    public function getUpdatedAt(): int;

    /**
     * @param integer $deletedAt
     * @return void
     */
    public function setDeletedAt(int $deletedAt): void;

    /**
     * @return integer|null
     */
    public function getDeletedAt(): ?int;

    /**
     * @param CommentsCollection $children
     * @return void
     */
    public function setChildren(CommentsCollection $children): void;

    /**
     * @param IComment $comment
     * @return void
     */
    public function addComment(IComment $comment): void;

    /**
     * @return CommentsCollection
     */
    public function getComments(): CommentsCollection;

    /**
     * @return integer
     */
    public function getChildCount(): int;

    /**
     * @param integer $id
     * @return void
     */
    public function removeCommentById(int $id): void;
}
