<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace RJM\CommentBundle\SpamDetection;

use RJM\CommentBundle\Model\CommentInterface;
use Ornicar\AkismetBundle\Akismet\AkismetInterface;

/**
 * Detects spam by querying the Akismet service.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class AkismetSpamDetection implements SpamDetectionInterface
{
    /**
     * @var AkismetInterface
     */
    protected $akismet;

    /**
     * @param AkismetInterface $akismet
     */
    public function __construct(AkismetInterface $akismet)
    {
        $this->akismet = $akismet;
    }

    /**
     * Returns true if Akismet believes the comment to be spam.
     *
     * @param  CommentInterface $comment
     * @return bool
     */
    public function isSpam(CommentInterface $comment)
    {
        return $this->akismet->isSpam($this->getCommentData($comment));
    }

    /**
     * Compiles comment data into a format Akismet accepts.
     *
     * @param  CommentInterface $comment
     * @return array
     */
    protected function getCommentData(CommentInterface $comment)
    {
        $data = array(
            'comment_type'    => 'comment',
            'comment_content' => $comment->getBody()
        );

        $data['comment_author'] = $comment->getAuthorName();

        return $data;
    }
}
