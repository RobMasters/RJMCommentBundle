<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\Document;

use RJM\CommentBundle\Model\Vote as BaseVote;

/**
 * Default ODM implementation of VoteInterface.
 *
 * Must be extended and properly mapped by the developer.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
abstract class Vote extends BaseVote
{

}
