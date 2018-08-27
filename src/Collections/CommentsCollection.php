<?php

namespace Balaremember\LaravelCommentsService\Collection;

use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\DatabaseAbstractLayer\DatabaseCommentIterator;
use Illuminate\Support\Collection;
use Traversable;

class CommentsCollection extends Collection
{
    /**
     * @var integer
     */
    public $pageNumber;

    /**
     * @var CommentService
     */
    private $service;

    /**
     * CommentsCollection constructor.
     * @param array          $items
     * @param CommentService $service
     * @param integer        $pageNumber
     */
    public function __construct(array $items, CommentService $service, int $pageNumber = 1)
    {
        $this->service = $service;
        parent::__construct($items);
        $this->pageNumber = $pageNumber;
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items), $this->service);
    }

    /**
     * Merge the collection with the given items.
     *
     * @param  mixed $items
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)), $this->service);
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new DatabaseCommentIterator($this, $this->service, $this->pageNumber);
    }
}
