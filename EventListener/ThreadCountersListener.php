<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\EventListener;

use RJM\CommentBundle\Events;
use RJM\CommentBundle\Event\CommentEvent;
use RJM\CommentBundle\Model\CommentManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * A listener that updates thread counters when a new comment is made.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class ThreadCountersListener implements EventSubscriberInterface
{
    private $commentManager;

    public function __construct(CommentManagerInterface $commentManager)
    {
        $this->commentManager = $commentManager;
    }

    public function onCommentPersist(CommentEvent $event)
    {
        $comment = $event->getComment();

        if (!$this->commentManager->isNewComment($comment)) {
            return;
        }

        $thread = $comment->getThread();
        $thread->incrementNumComments(1);
        $thread->setLastCommentAt($comment->getCreatedAt());
    }

    public static function getSubscribedEvents()
    {
        return array(Events::COMMENT_PRE_PERSIST => 'onCommentPersist');
    }
}
