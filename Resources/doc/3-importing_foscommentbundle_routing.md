Step 3: Import RJMCommentBundle routing
=======================================
Import the bundle routing:

``` yaml
rjm_comment_api:
    type: rest
    resource: "@RJMCommentBundle/Resources/config/routing.yml"
    prefix: /api
    defaults: { _format: html }
```
**Note:**

> The `type: rest` part is important.

**Note:**

> The defaults configuration may not be necessary unless you have
> changed RJMRestBundle's default format.

### Continue to the next step! (final!)
When you're done. Continue with the final step: enabling the comments on a page:
[Step 4: Enable comments on a page](4-enable_comments_on_a_page.md).
