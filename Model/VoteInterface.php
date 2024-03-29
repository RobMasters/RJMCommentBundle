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

use Symfony\Component\Validator\ExecutionContext;

/**
 * Methods a vote should implement.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface VoteInterface
{
    const VOTE_UP = 1;
    const VOTE_DOWN = -1;

    /**
     * @return mixed unique ID for this vote
     */
    public function getId();

    /**
     * @return SignedCommentInterface
     */
    public function getComment();

    /**
     * @param VotableCommentInterface $comment
     */
    public function setComment(VotableCommentInterface $comment);

    /**
     * @return integer the modification applied to the comment by this vote
     */
    public function getValue();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @param ExecutionContext $context
     */
    public function isVoteValid(ExecutionContext $context);
}
