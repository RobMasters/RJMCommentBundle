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

use RJM\CommentBundle\Model\VoteInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An event that occurs related to a vote.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class VoteEvent extends Event
{
    private $vote;

    /**
     * Constructs an event.
     *
     * @param \RJM\CommentBundle\Model\VoteInterface $vote
     */
    public function __construct(VoteInterface $vote)
    {
        $this->vote = $vote;
    }

    /**
     * Returns the vote for the event.
     *
     * @return \RJM\CommentBundle\Model\VoteInterface
     */
    public function getVote()
    {
        return $this->vote;
    }
}
