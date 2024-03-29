Step 10: Other ways of including comments in a page
======================================

The default implementation of RJMCommentBundle uses asynchronous javascript
and jQuery (optionally with easyXDM for cross domain requests) to load a comment
thread into a page.

It is possible to include the thread without using javascript to load it, but
needs additional work inside the controller's action.

At a minimum, you will need to include the following in your action's PHP code:

``` php
public function somethingAction(Request $request)
{
    $id = 'thread_id';
    $thread = $this->container->get('rjm_comment.manager.thread')->findThreadById($id);
    if (null === $thread) {
        $thread = $this->container->get('rjm_comment.manager.thread')->createThread();
        $thread->setId($id);
        $thread->setPermalink($request->getUri());

        // Add the thread
        $this->container->get('rjm_comment.manager.thread')->saveThread($thread);
    }

    $comments = $this->container->get('rjm_comment.manager.comment')->findCommentTreeByThread($thread);

    return $this->render('AcmeDemoBundle:Controller:something.html.twig', array(
        'comments' => $comments,
        'thread' => $thread,
    ));
}
```

Once you've included this code in your action, some code must be included in your
template:

``` jinga
{% block body %}
{# ... #}
<div id="rjm_comment_thread" data-thread="{{ thread.id }}">

{% include 'RJMCommentBundle:Thread:comments.html.twig' with {
    'comments': comments,
    'thread': thread
} %}

</div>
{# ... #}
{% endblock body %}

{% block javascript %}
{# jQuery must be available in the page by this time #}
{% javascripts '@RJMCommentBundle/Resources/assets/js/comments.js' %}
<script type="text/javascript" src="{{ asset_url }}">
// URI identifier for the thread comments
var rjm_comment_thread_id = '{{ path('rjm_comment_get_thread_comments', {'id': thread.id}) }}';
</script>
{% endjavascripts %}
{% endblock javascript %}

```

## That is it!
[Return to the index.](index.md)
