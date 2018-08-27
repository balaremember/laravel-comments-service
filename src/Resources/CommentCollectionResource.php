<?php

namespace Balaremember\LaravelCommentsService\Resources;

class CommentCollectionResource extends AbstractCollectionResource
{
    /**
     * @param $item
     * @return CommentResource
     */
    protected function transformSingle($item)
    {
        return new CommentResource($item);
    }
}