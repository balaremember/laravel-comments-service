<?php

namespace Balaremember\LaravelCommentsService\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class AbstractCollectionResource extends ResourceCollection
{
    /**
     * @param $item
     * @return mixed
     */
    abstract protected function transformSingle($item);

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function ($item) {
            return $this->transformSingle($item);
        });
        return parent::toArray($request);
    }
}
