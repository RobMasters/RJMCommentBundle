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

use RJM\CommentBundle\Model\VoteInterface;
use RJM\CommentBundle\Model\VotableCommentInterface;

/**
 * Used for checking if the ACL system will allow specific actions
 * to occur.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface VoteAclInterface
{
    /**
     * Checks if the user should be allowed to create a vote.
     *
     * @param \RJM\CommentBundle\Model\VotableCommentInterface $comment
     * @return boolean
     */
    public function canCreate(VotableCommentInterface $comment);

    /**
     * Checks if the user should be allowed to view a vote.
     *
     * @param  VoteInterface $vote
     * @return boolean
     */
    public function canView(VoteInterface $vote);

    /**
     * Checks if the user should be allowed to edit a vote.
     *
     * @param  VoteInterface $vote
     * @return boolean
     */
    public function canEdit(VoteInterface $vote);

    /**
     * Checks if the user should be allowed to delete a vote.
     *
     * @param  VoteInterface $vote
     * @return boolean
     */
    public function canDelete(VoteInterface $vote);

    /**
     * Sets the default Acl permissions on a comment.
     *
     * Note: this does not remove any existing Acl and should only
     * be called on new CommentInterface instances.
     *
     * @param  VoteInterface $comment
     * @return void
     */
    public function setDefaultAcl(VoteInterface $vote);

    /**
     * Installs the Default 'fallback' Acl entries for generic access.
     *
     * @return void
     */
    public function installFallbackAcl();

    /**
     * Removes default Acl entries
     *
     * @return void
     */
    public function uninstallFallbackAcl();
}
