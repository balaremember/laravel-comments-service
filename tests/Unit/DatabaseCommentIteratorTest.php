<?php

namespace Balaremember\LaravelCommentsService\Test\Unit;

use Balaremember\LaravelCommentsService\Comments\Comment;
use Balaremember\LaravelCommentsService\CommentsServiceProvider;
use Balaremember\LaravelCommentsService\DatabaseAbstractLayer\DatabaseCommentIterator;
use Balaremember\LaravelCommentsService\Collections\CommentsCollection;
use Balaremember\LaravelCommentsService\Services\CommentService;
use \Mockery;
use Orchestra\Testbench\TestCase;

class DatabaseCommentIteratorTest extends TestCase
{
    /**
     * Setup the test environment.
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [CommentsServiceProvider::class];
    }

    /**
     * Clean up the testing environment before the next test.
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

    /**
     * @covers DatabaseCommentIterator::count
     * @return void
     */
    public function testCountWithEmptyCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $this->assertEquals(0, $iterator->count());
    }

    /**
     * @covers DatabaseCommentIterator::count
     * @return void
     */
    public function testCountWithNotEmptyCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([123], $serviceMock), $serviceMock, 1);
        $this->assertEquals(1, $iterator->count());
    }

    /**
     * @covers DatabaseCommentIterator::hasNext
     * @return void
     */
    public function testHasNext()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([123], $serviceMock), $serviceMock, 1);
        $this->assertTrue($iterator->hasNext());
    }

    /**
     * @covers DatabaseCommentIterator::currentParentId
     * @return void
     */
    public function testCurrentParentId()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $comment = new Comment($serviceMock);
        $comment->setParentId(1);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([$comment], $serviceMock), $serviceMock, 1);
        $this->assertEquals(1, $iterator->currentParentId());
    }

    /**
     * @covers DatabaseCommentIterator::offsetExists
     * @return void
     */
    public function testOffsetExistsWithEmptyCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $this->assertFalse($iterator->offsetExists(0));
    }

    /**
     * @covers DatabaseCommentIterator::offsetExists
     * @return void
     */
    public function testOffsetExistsWithNotEmptyCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([$commentEntity], $serviceMock), $serviceMock, 1);
        $this->assertTrue($iterator->offsetExists(0));
    }

    /**
     * @covers DatabaseCommentIterator::offsetGet
     * @return void
     */
    public function testOffsetGetWithEmptyCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $this->assertEquals(null, $iterator->offsetGet(0));
    }

    /**
     * @covers DatabaseCommentIterator::offsetGet
     * @return void
     */
    public function testOffsetGetWithNotEmptyCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([$commentEntity], $serviceMock), $serviceMock, 1);
        $this->assertEquals($commentEntity, $iterator->offsetGet(0));
    }

    /**
     * @covers DatabaseCommentIterator::offsetSet
     * @return void
     */
    public function testOffsetSet()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $iterator->offsetSet(0, $commentEntity);
        $this->assertEquals($commentEntity, $iterator->offsetGet(0));
    }

    /**
     * @covers DatabaseCommentIterator::offsetSet
     * @return void
     */
    public function testOffsetSetWithNull()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $iterator->offsetSet(null, $commentEntity);
        $this->assertEquals($commentEntity, $iterator->offsetGet(0));
    }

    /**
     * @covers DatabaseCommentIterator::offsetUnset
     * @return void
     */
    public function testOffsetUnset()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([$commentEntity], $serviceMock), $serviceMock, 1);
        $iterator->offsetUnset(0);
        $this->assertEquals(null, $iterator->offsetGet(0));
    }

    /**
     * @covers DatabaseCommentIterator::current
     * @return void
     */
    public function testCurrentWithNotEndCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([$commentEntity], $serviceMock), $serviceMock, 1);
        $this->assertEquals($commentEntity, $iterator->current());
    }

    /**
     * @covers DatabaseCommentIterator::current
     * @return void
     */
    public function testCurrentWithEndCollection()
    {
        $serviceMock = Mockery::mock(CommentService::class);

        $nextEntityComment = new Comment($serviceMock);
        $nextEntityComment->setId(11);
        $nextEntityComment->setUserId(2);
        $nextEntityComment->setEntityId(2);
        $nextEntityComment->setEntityType('document');
        $nextEntityComment->setParentId(1);
        $nextEntityComment->setMessage('asbc');
        $nextEntityComment->setCreatedAt(123123);
        $nextEntityComment->setUpdatedAt(123123);
        $nextEntityComment->setDeletedAt(null);

        $collection = new CommentsCollection([], $serviceMock);

        for ($i = 0; $i < 10; $i++) {
            $id = $i+1;
            $currentComment = new Comment($serviceMock);
            $currentComment->setId($id);
            $currentComment->setUserId(1);
            $currentComment->setEntityId(1);
            $currentComment->setEntityType('document');
            $currentComment->setParentId(1);
            $currentComment->setMessage('abc');
            $currentComment->setCreatedAt(123123);
            $currentComment->setUpdatedAt(123123);
            $currentComment->setDeletedAt(null);
            $collection->push($currentComment);
        }

        $nextPageCommentsCollection = new CommentsCollection([$nextEntityComment], $serviceMock);

        $serviceMock->shouldReceive('getCommentsTreeByRootCommentId')->once()->with(1, 2)->andReturn($nextPageCommentsCollection);

        $iterator = new DatabaseCommentIterator($collection, $serviceMock, 1);

        while ($iterator->hasNext()) {
            $iterator->next();
        }

        $this->assertEquals($nextEntityComment, $iterator->current());
    }

    /**
     * @covers DatabaseCommentIterator::next
     * @return void
     */
    public function testNext()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $iterator->next();
        $this->assertEquals(1, $iterator->key());
    }

    /**
     * @covers DatabaseCommentIterator::key
     * @return void
     */
    public function testKey()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $this->assertEquals(0, $iterator->key());
    }

    /**
     * @covers DatabaseCommentIterator::key
     * @return void
     */
    public function testKeyAfterNext()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $iterator->next();
        $this->assertEquals(1, $iterator->key());
    }

    /**
     * @covers DatabaseCommentIterator::valid
     * @return void
     */
    public function testValidSetPosition()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $commentEntity = new Comment($serviceMock);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([$commentEntity], $serviceMock), $serviceMock, 1);
        $this->assertTrue($iterator->valid());
    }

    /**
     * @covers DatabaseCommentIterator::valid
     * @return void
     */
    public function testValidUnsetPosition()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $this->assertFalse($iterator->valid());
    }

    /**
     * @covers DatabaseCommentIterator::rewind
     * @return void
     */
    public function testRewind()
    {
        $serviceMock = Mockery::mock(CommentService::class);
        $iterator = new DatabaseCommentIterator(new CommentsCollection([], $serviceMock), $serviceMock, 1);
        $iterator->next();
        $iterator->rewind();
        $this->assertEquals(0, $iterator->key());
    }
}
