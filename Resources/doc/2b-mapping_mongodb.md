Step 2b: Setup MongoDB mapping
==============================
The MongoDB implementation does not provide a concrete Comment class for your use,
you must create one:

``` php
<?php
// src/MyProject/MyBundle/Document/Comment.php

namespace MyProject\MyBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use RJM\CommentBundle\Document\Comment as BaseComment;

/**
 * @MongoDB\Document
 * @MongoDB\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @MongoDB\ReferenceOne(targetDocument="MyProject\MyBundle\Document\Thread")
     */
    protected $thread;
}
```

Additionally, create the Thread class:

``` php
<?php
// src/MyProject/MyBundle/Document/Thread.php

namespace MyProject\MyBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use RJM\CommentBundle\Document\Thread as BaseThread;

/**
 * @MongoDB\Document
 * @MongoDB\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Thread extends BaseThread
{

}
```

## Configure your application

In YAML:

``` yaml
# app/config/config.yml

rjm_comment:
    db_driver: mongodb
    class:
        model:
            comment: MyProject\MyBundle\Document\Comment
            thread: MyProject\MyBundle\Document\Thread

assetic:
    bundles: [ "RJMCommentBundle" ]
```

Or if you prefer XML:

``` xml
# app/config/config.xml

<rjm_comment:config db-driver="mongodb">
    <rjm_comment:class>
        <rjm_comment:model
            comment="MyProject\MyBundle\Document\Comment"
            thread="MyProject\MyBundle\Document\Thread"
        />
    </rjm_comment:class>
</rjm_comment:config>
    
<assetic:config>
    <assetic:bundle name="RJMCommentBundle" />
</assetic:config>
```

### Back to the main step
[Step 2: Create your Comment and Thread classes](2-create_your_comment_and_thread_classes.md).
