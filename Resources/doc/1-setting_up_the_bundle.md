Step 1: Setting up the bundle
=============================
### A) Download and install RJMCommentBundle

To install RJMCommentBundle run the following command

``` bash
$ php composer.phar require friendsofsymfony/comment-bundle
```

### B) Enable the bundle

Finally, enable the required bundles in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new RJM\RestBundle\RJMRestBundle(),
        new RJM\CommentBundle\RJMCommentBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle($this),
    );
}
```

### Continue to the next step!
When you're done. Continue by creating the appropriate Comment and Thread classes:
[Step 2: Create your Comment and Thread classes](2-create_your_comment_and_thread_classes.md).
