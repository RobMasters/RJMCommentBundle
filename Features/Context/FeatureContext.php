<?php

namespace RJM\CommentBundle\Features\Context;

use Behat\BehatBundle\Context\MinkContext;

/**
 * Feature context.
 */
class FeatureContext extends MinkContext
{
    /**
     * @Given /^I have a thread identified by "([^"]*)" with a link of "([^"]*)"$/
     */
    public function iHaveAThreadIdentifiedBy($id, $link)
    {
        $thread = $this->getThreadManager()->findThreadById($id);
        if (!$thread) {
            $thread = $this->getThreadManager()->createThread($id);
        }

        $thread->setPermalink($link);

        $this->getThreadManager()->saveThread($thread);
    }

    /**
     * @return \RJM\CommentBundle\Model\ThreadManagerInterface
     */
    private function getThreadManager()
    {
        return $this->getContainer()->get('rjm_comment.manager.thread');
    }
}
