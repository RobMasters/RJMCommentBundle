Step 2a: Setup Doctrine ORM mapping
===================================
The ORM implementation does not provide a concrete Comment class for your use,
you must create one. This can be done by extending the abstract entities
provided by the bundle and creating the appropriate mappings.

For example:

``` php
<?php
// src/MyProject/MyBundle/Entity/Comment.php

namespace MyProject\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RJM\CommentBundle\Entity\Comment as BaseComment;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="MyProject\MyBundle\Entity\Thread")
     */
    protected $thread;
}
```

And the Thread:

``` php
<?php
// src/MyProject/MyBundle/Entity/Thread.php

namespace MyProject\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RJM\CommentBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Thread extends BaseThread
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;
}
```

## Configure your application

``` yaml
# app/config/config.yml

rjm_comment:
    db_driver: orm
    class:
        model:
            comment: MyProject\MyBundle\Entity\Comment
            thread: MyProject\MyBundle\Entity\Thread

assetic:
    bundles: [ "RJMCommentBundle" ]
```

Or if you prefer XML:

``` xml
# app/config/config.xml
<rjm_comment:config db-driver="orm">
    <rjm_comment:class>
        <rjm_comment:model
            comment="MyProject\MyBundle\Entity\Comment"
            thread="MyProject\MyBundle\Entity\Thread"
        />
    </rjm_comment:class>
</rjm_comment:config>

<assetic:config>
    <assetic:bundle name="RJMCommentBundle" />
</assetic:config>
```
### Back to the main step
[Step 2: Create your Comment and Thread classes](2-create_your_comment_and_thread_classes.md).
