<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\Event;

use RJM\CommentBundle\Model\ThreadInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a thread.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class ThreadEvent extends Event
{
    private $thread;

    /**
     * Constructs an event.
     *
     * @param \RJM\CommentBundle\Model\ThreadInterface $thread
     */
    public function __construct(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Returns the thread for this event.
     *
     * @return \RJM\CommentBundle\Model\ThreadInterface
     */
    public function getThread()
    {
        return $this->thread;
    }
}
