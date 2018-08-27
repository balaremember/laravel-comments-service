<?php

namespace Balaremember\LaravelCommentsService\Resources;

use Balaremember\LaravelCommentsService\Entities\Comment;
use Balaremember\LaravelCommentsService\Contracts\IComment;
use Balaremember\LaravelCommentsService\Comments\LazyComment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CommentResource
 * @package App\Http\Resources
 *
 * @property Comment $resource
 */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var IComment $resource
         */
        $resource = $this->resource;

        return [
            'id' => $resource->getId(),
            'user_id' => $resource->getUserId(),
            'commentable_id' => $resource->getEntityId(),
            'commentable_type' => $resource->getEntityType(),
            'parent_id' => $resource->getParentId(),
            'body' => $resource->getMessage(),
            'created_at' => $resource->getCreatedAt(),
            'updated_at' => $resource->getUpdatedAt(),
            'deleted_at' => $resource->getDeletedAt(),
            'children' => $resource instanceof LazyComment ? null : $resource->getComments()->map(function ($item) use ($request) {
                return (new CommentResource($item))->toArray($request);
            }),
        ];
    }
}
