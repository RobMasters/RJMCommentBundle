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
use RJM\CommentBundle\Event\ThreadEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Responsible for setting a permalink for each new Thread object.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com
 */
class ThreadPermalinkListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Creates and persists a thread with the specified id.
     *
     * @param \RJM\CommentBundle\Event\ThreadEvent $event
     */
    public function onThreadCreate(ThreadEvent $event)
    {
        if (!$this->container->isScopeActive('request')) {
            return;
        }

        $thread = $event->getThread();
        $thread->setPermalink($this->container->get('request')->getUri());
    }

    public static function getSubscribedEvents()
    {
        return array(Events::THREAD_CREATE => 'onThreadCreate');
    }
}
