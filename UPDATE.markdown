1.0.0 to 1.1.0

 * `Resources/Thread/comment.html.twig` has changed, adding a rawBody option. This change is not relevant unless you are going to use RawComments
 * If you don't use the async template to render the comments, you will need to add a new variable defining the base url of the api:

 ``` javascript
 var rjm_comment_thread_api_base_url = 'http://example.org/api/threads';
 var rjm_comment_thread_id = 'my_thread_id';
 ```
 * A new method `ThreadManagerInterface#findThreadsBy` was added.
 * A new method `ThreadManagerInterface#isNewThread()` was added.
 * `ThreadInterface#setIsCommentable` was renamed to `ThreadInterface#setCommentable`
 * A new method `CommentManagerInterface#isNewComment` was added.
 * The html class `rjm_comment_comment_form` was renamed to `rjm_comment_comment_new_form`. Custom javascript implementations should be adjusted for this change.
 * A new method `CommentInterface#getState` was added.
 * A new method `CommentInterface#setState` was added.
 * A new method `CommentInterface#getPreviousState` was added.
 * A new field was added to `Document\Comment` and `Entity\Comment`. ORM users should update their schema.


0.9.2 to 1.0.0

 * You need to remove comment.js previously used by this bundle. async.html.twig now includes its
   own javascript file automatically.
 * There is now a dependency on RJMRestBundle. Check the installation documentation for details.
 * Routing has changed, you must replace your existing rjm_comment route import to

   ``` yaml
   rjm_comment_api:
       type: rest
       resource: "@RJMCommentBundle/Resources/config/routing.yml"
       prefix: /comment/api
   ```

 * The way to include comments in a page has changed, importing an asynchronous javascript template into
   your page which will trigger an asynchronous load of a comment thread using the REST api.

   ``` jinja
   {% include 'RJMCommentBundle:Thread:async.html.twig' with {'id': 'foo'} %}
   ```

2012-01-21

 * Blamers, Creators and Spam Detection classes have been moved to an Event Dispatcher based set up.
   Documentation on how to use this feature is expected to be available with the release of v1.0.0
 * CommentManager, ThreadManager and VoteManager's interfaces have changed slightly, renaming add*()
   methods to save*().

2011-08-10

 * ORM: Column names like ``createdAt`` have been changed to underscore delimited format ``created_at``.
   Schema update and cache clearance is required for migration.

2011-08-08

 * Thread property ``identifier`` has been renamed to ``id``
 * ORM: Comment property ancestors has been marked as not null and should default to an empty string

2011-07-31

 * The supplied Thread classes are now mapped-superclasses, you must extend and implement an appropriate
   class from Entity/ or Document/ for your application and adjust your configuration accordingly.
