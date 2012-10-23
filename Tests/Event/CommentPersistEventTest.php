<?php

namespace RJM\CommentBundle\Tests\Event;

use RJM\CommentBundle\Event\CommentPersistEvent;

class CommentPersistEventTest extends \PHPUnit_Framework_TestCase
{
    public function testAbortingPersistence()
    {
        $comment = $this->getMock('RJM\CommentBundle\Model\CommentInterface');
        $event = new CommentPersistEvent($comment);
        $this->assertFalse($event->isPersistenceAborted());
        $event->abortPersistence();
        $this->assertTrue($event->isPersistenceAborted());
    }
}
