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
use RJM\CommentBundle\Markup\ParserInterface;
use RJM\CommentBundle\Model\RawCommentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Parses a comment for markup and sets the result
 * into the rawBody property.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class CommentMarkupListener implements EventSubscriberInterface
{
    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Constructor.
     *
     * @param \RJM\CommentBundle\Markup\ParserInterface $parser
     */
    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Parses raw comment data and assigns it to the rawBody
     * property.
     *
     * @param \RJM\CommentBundle\Event\CommentEvent $event
     */
    public function markup(CommentEvent $event)
    {
        $comment = $event->getComment();

        if (!$comment instanceof RawCommentInterface) {
            return;
        }

        $result = $this->parser->parse($comment->getBody());
        $comment->setRawBody($result);
    }

    public static function getSubscribedEvents()
    {
        return array(Events::COMMENT_PRE_PERSIST => 'markup');
    }
}
