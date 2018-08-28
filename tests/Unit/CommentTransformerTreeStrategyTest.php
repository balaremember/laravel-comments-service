<?php

namespace Balaremember\LaravelCommentsService\Test\Unit;

use Balaremember\LaravelCommentsService\Collection\CommentsCollection;
use Balaremember\LaravelCommentsService\Comments\LazyComment;
use Balaremember\LaravelCommentsService\Entities\Comment;
use Balaremember\LaravelCommentsService\Service\CommentService;
use Balaremember\LaravelCommentsService\Strategy\CommentTransformerTreeStrategy;
use Balaremember\LaravelCommentsService\Comments\Comment as CommentEntity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use \Mockery;
use PHPUnit\Framework\TestCase;

class CommentTransformerTreeStrategyTest extends TestCase
{
    /**
     * Setup the test environment.
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
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
     * @covers CommentTransformerTreeStrategy::make
     * @return void
     */
    public function testMake()
    {
        $serviceMock = Mockery::mock(CommentService::class);

        $strategy = new CommentTransformerTreeStrategy($serviceMock);

        $rootComment = App::factory(Comment::class)->make([
            'id' => 1,
            'parent_id' => null,
            'commentable_id' => 1
        ]);

        $firstLvlComment = App::factory(Comment::class)->make([
            'id' => 2,
            'commentable_id' => 1,
            'parent_id' => $rootComment->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $secondLvlComment = App::factory(Comment::class)->make([
            'id' => 3,
            'commentable_id' => 1,
            'parent_id' => $firstLvlComment->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $thirdLvlComment = App::factory(Comment::class)->make([
            'id' => 4,
            'commentable_id' => 1,
            'parent_id' => $secondLvlComment->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $modelCollection = new Collection([]);
        $modelCollection = $modelCollection->push($firstLvlComment)->push($secondLvlComment)->push($thirdLvlComment);

        $commentFirstLevelEntity = new CommentEntity($serviceMock);
        $commentFirstLevelEntity->setId($firstLvlComment->id);
        $commentFirstLevelEntity->setUserId($firstLvlComment->user_id);
        $commentFirstLevelEntity->setEntityId($firstLvlComment->commentable_id);
        $commentFirstLevelEntity->setEntityType($firstLvlComment->commentable_type);
        $commentFirstLevelEntity->setParentId($firstLvlComment->parent_id);
        $commentFirstLevelEntity->setMessage($firstLvlComment->body);
        $commentFirstLevelEntity->setCreatedAt($firstLvlComment->created_at->timestamp);
        $commentFirstLevelEntity->setUpdatedAt($firstLvlComment->updated_at->timestamp);
        $commentFirstLevelEntity->setDeletedAt($firstLvlComment->deleted_at);

        $commentSecondLevelEntity = new CommentEntity($serviceMock);
        $commentSecondLevelEntity->setId($secondLvlComment->id);
        $commentSecondLevelEntity->setUserId($secondLvlComment->user_id);
        $commentSecondLevelEntity->setEntityId($secondLvlComment->commentable_id);
        $commentSecondLevelEntity->setEntityType($secondLvlComment->commentable_type);
        $commentSecondLevelEntity->setParentId($secondLvlComment->parent_id);
        $commentSecondLevelEntity->setMessage($secondLvlComment->body);
        $commentSecondLevelEntity->setCreatedAt($secondLvlComment->created_at->timestamp);
        $commentSecondLevelEntity->setUpdatedAt($secondLvlComment->updated_at->timestamp);
        $commentSecondLevelEntity->setDeletedAt($secondLvlComment->deleted_at);
        $commentSecondLevelEntityCollection = new CommentsCollection([$commentSecondLevelEntity], $serviceMock);

        $commentThirdLevelEntity = new LazyComment($serviceMock);
        $commentThirdLevelEntity->setId($thirdLvlComment->id);
        $commentThirdLevelEntity->setUserId($thirdLvlComment->user_id);
        $commentThirdLevelEntity->setEntityId($thirdLvlComment->commentable_id);
        $commentThirdLevelEntity->setEntityType($thirdLvlComment->commentable_type);
        $commentThirdLevelEntity->setParentId($thirdLvlComment->parent_id);
        $commentThirdLevelEntity->setMessage($thirdLvlComment->body);
        $commentThirdLevelEntity->setCreatedAt($thirdLvlComment->created_at->timestamp);
        $commentThirdLevelEntity->setUpdatedAt($thirdLvlComment->updated_at->timestamp);
        $commentThirdLevelEntity->setDeletedAt($thirdLvlComment->deleted_at);
        $commentThirdLevelEntityCollection = new CommentsCollection([$commentThirdLevelEntity], $serviceMock);

        $commentSecondLevelEntity->setChildren($commentThirdLevelEntityCollection);
        $commentFirstLevelEntity->setChildren($commentSecondLevelEntityCollection);

        $tree = new CommentsCollection([], $serviceMock);
        $tree->push($commentFirstLevelEntity);

        $this->assertEquals($tree, $strategy->make($modelCollection, 3, 1, $rootComment->id));
    }
}
