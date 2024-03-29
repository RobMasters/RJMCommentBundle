<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\Model;

/**
 * A comment that may be voted on.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface VotableCommentInterface extends CommentInterface
{
    /**
     * Get a collection of votes for this comment
     *
     * @return \RJM\CommentBundle\Model\SignedVoteInterface[]
     */
    public function getVotes();

    /**
     * Sets the score of the comment.
     *
     * @param integer $score
     */
    public function setScore($score);

    /**
     * Returns the current score of the comment.
     *
     * @return integer
     */
    public function getScore();

    /**
     * Get the total number of likes
     *
     * @return integer
     */
    public function getLikes();

    /**
     * Get the total number of dislikes
     *
     * @return integer
     */
    public function getDislikes();

    /**
     * Increments the comment score by the provided
     * value.
     *
     * @param integer value
     * @return integer The new comment score
     */
    public function incrementScore($by = 1);
}
