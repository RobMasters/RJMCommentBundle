<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\Tests\Acl;

use RJM\CommentBundle\Acl\RoleVoteAcl;

/**
 * Tests the functionality provided by Acl\AclVoteManager.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class RoleVoteAclTest extends AbstractRoleAcl
{
    public function setup()
    {
        parent::setup();

        $this->roleAcl = new RoleVoteAcl($this->securityContext,
            $this->createRole,
            $this->viewRole,
            $this->editRole,
            $this->deleteRole,
            '');
        $this->passObject = $this->getMock('RJM\CommentBundle\Model\VoteInterface');
    }
}
