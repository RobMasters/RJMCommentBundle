<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\Acl;

use RJM\CommentBundle\Model\VotableCommentInterface;
use RJM\CommentBundle\Model\VoteInterface;
use RJM\CommentBundle\Model\VoteManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Wraps a real implementation of VoteManagerInterface and
 * performs Acl checks with the configured Vote Acl service.
 *
 * @author Tim Nagel <tim@nagel.com.au
 */
class AclVoteManager implements VoteManagerInterface
{
    /**
     * The VoteManager instance to be wrapped with ACL.
     *
     * @var VoteManagerInterface
     */
    private $realManager;

    /**
     * The Vote Acl instance for querying Acls.
     *
     * @var VoteAclInterface
     */
    private $voteAcl;

    /**
     * The Comment Acl for querying Acls.
     *
     * @var CommentAclInterface
     */
    private $commentAcl;

    /**
     * {@inheritDoc}
     */
    public function __construct(VoteManagerInterface $voteManager, VoteAclInterface $voteAcl, CommentAclInterface $commentAcl)
    {
        $this->realManager = $voteManager;
        $this->voteAcl = $voteAcl;
        $this->commentAcl = $commentAcl;
    }

    /**
     * {@inheritDoc}
     */
    public function findVoteById($id)
    {
        $vote = $this->realManager->findVoteById($id);

        if (!$this->voteAcl->canView($vote)) {
            throw new AccessDeniedException();
        }

        return $vote;
    }

    /**
     * {@inheritDoc}
     */
    public function findVoteBy(array $criteria)
    {
        $vote = $this->realManager->findVoteBy($criteria);

        if (!$this->voteAcl->canView($vote)) {
            throw new AccessDeniedException();
        }

        return $vote;
    }

    /**
     * {@inheritDoc}
     */
    public function findVotesByComment(VotableCommentInterface $comment)
    {
        $votes = $this->realManager->findVotesByComment($comment);

        foreach ($votes as $vote) {
            if (!$this->voteAcl->canView($vote)) {
                throw new AccessDeniedException();
            }
        }

        return $votes;
    }

    /**
     * {@inheritDoc}
     */
    public function saveVote(VoteInterface $vote)
    {
        if (!$this->voteAcl->canCreate($vote->getComment())) {
            throw new AccessDeniedException();
        }

        if (!$this->commentAcl->canView($vote->getComment())) {
            throw new AccessDeniedException();
        }

        $this->realManager->saveVote($vote);
        $this->voteAcl->setDefaultAcl($vote);
    }

    /**
     * {@inheritDoc}
     */
    public function createVote(VotableCommentInterface $comment)
    {
        return $this->realManager->createVote($comment);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->realManager->getClass();
    }
}
