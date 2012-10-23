<?php

/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return array(
    new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),

    new Symfony\Bundle\AsseticBundle\AsseticBundle(),
    new Symfony\Bundle\SecurityBundle\SecurityBundle(),
    new Symfony\Bundle\TwigBundle\TwigBundle(),

    new Behat\BehatBundle\BehatBundle(),
    new Behat\MinkBundle\MinkBundle(),

    new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

    new RJM\RestBundle\RJMRestBundle(),
    new RJM\CommentBundle\RJMCommentBundle(),

    new JMS\SerializerBundle\JMSSerializerBundle($this),

    new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

    new RJM\CommentBundle\Tests\Functional\Bundle\CommentBundle\CommentBundle(),
);
