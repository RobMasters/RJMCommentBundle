Step 9b: Using the Sundown PECL extension
======================================

The markup system in RJMCommentBundle is flexible and allows you to use any
syntax language that a parser exists for. PECL has an extension for markdown
parsing called Sundown, which is faster than pure PHP implementations of a
markdown parser.

RJMCommentBundle doesnt ship with a bridge for this extension, but it is
trivial to implement.

First, you will need to use PECL to install Sundown. `pecl install sundown`.

You will want to create the service below in one of your application bundles.

``` php
<?php
// src/Vendor/CommentBundle/Markup/Sundown.php

namespace Vendor\CommentBundle\Markup;

use RJM\CommentBundle\Markup\ParserInterface;
use Sundown\Markdown;

class Sundown implements ParserInterface
{
    private $parser;

    protected function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new Markdown(\Sundown\Render\HTML, array(
                'autolink' => true,
                'filter_html' => true,
            ));
        }

        return $this->parser;
    }

    public function parse($raw)
    {
        return $this->getParser()->render($raw);
    }
}
```

And the service definition to enable this parser bridge

``` yaml
# app/config/config.yml

services:
    # ...
    markup.sundown_markdown:
        class: Vendor\CommentBundle\Markup\Sundown
    # ...

rjm_comment:
    # ...
    services:
        markup: markup.sundown_markdown
    # ...
```

## That is it!
[Return to the index.](index.md)
