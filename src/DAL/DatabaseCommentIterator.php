<?php

namespace Balaremember\LaravelCommentsService\DatabaseAbstractLayer;

use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use ArrayAccess;
use Countable;
use Iterator;

class DatabaseCommentIterator implements ArrayAccess, Countable, Iterator
{
    /**
     * @var CommentsCollection
     */
    private $collection;

    /**
     * @var integer
     */
    private $position;

    /**
     * @var integer
     */
    private $pageNumber;

    /**
     * @var CommentService
     */
    private $service;

    /**
     * DatabaseCommentIterator constructor.
     * @param CommentsCollection $collection
     * @param CommentService     $service
     * @param integer            $pageNumber
     */
    public function __construct(CommentsCollection $collection, CommentService $service, int $pageNumber)
    {
        $this->position   = 0;
        $this->service    = $service;
        $this->collection = $collection;
        $this->pageNumber = $pageNumber;
    }

    /**
     * @return boolean
     */
    public function hasNext(): bool
    {
        return $this->collection->count() > $this->position;
    }

    /**
     * Return parent id of current comment object
     * @return integer|null
     */
    public function currentParentId(): ?int
    {
        return $this->collection[$this->position]->getParentId();
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset
     * An offset to check for.
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset
     * The offset to retrieve.
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset
     * The offset to assign the value to.
     * @param mixed $value
     *  The value to set.
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return integer The custom count as an integer.
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if ($this->hasNext() == false) {
            $this->pageNumber++;
            $nextPageComments = $this->service->getCommentsTreeByRootCommentId($this->collection[--$this->position]->getParentId(), $this->pageNumber);
            $this->collection = $this->collection->merge($nextPageComments);
            $this->next();
            if ($this->valid()) {
                return $this->collection[$this->position];
            }
        }

        return $this->collection[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->collection[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
