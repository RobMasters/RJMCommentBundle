/**
 * This file is part of the RJMCommentBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * To use this reference javascript, you must also have jQuery installed. If
 * you want to embed comments cross-domain, then easyXDM CORS is also required.
 *
 * @todo: expand this explanation (also in the docs)
 *
 * Then a comment thread can be embedded on any page:
 *
 * <div id="rjm_comment_thread">#comments</div>
 * <script type="text/javascript">
 *     // Set the thread_id if you want comments to be loaded via ajax (url to thread comments api)
 *     var rjm_comment_thread_id = 'a_unique_identifier_for_the_thread';
 *     var rjm_comment_thread_api_base_url = 'http://example.org/api/threads';
 *
 *     // Optionally set the cors url if you want cross-domain AJAX (also needs easyXDM)
 *     var rjm_comment_remote_cors_url = 'http://example.org/cors/index.html';
 *
 *     // Optionally set a custom callback function to update the comment count elements
 *     var window.rjm_comment_thread_comment_count_callback = function(elem, threadObject){}
 *
 *     // Optionally set a different element than div#rjm_comment_thread as container
 *     var rjm_comment_thread_container = $('#other_element');
 *
 * (function() {
 *     var rjm_comment_script = document.createElement('script');
 *     rjm_comment_script.async = true;
 *     rjm_comment_script.src = 'http://example.org/path/to/this/file.js';
 *     rjm_comment_script.type = 'text/javascript';
 *
 *     (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(rjm_comment_script);
 * })();
 * </script>
 */

(function(window, $, easyXDM){
    console.log('loading comments js...');
    "use strict";
    var RJM_COMMENT = {
        /**
         * Shorcut post method.
         *
         * @param string url The url of the page to post.
         * @param object data The data to be posted.
         * @param function success Optional callback function to use in case of succes.
         * @param function error Optional callback function to use in case of error.
         */
        post: function(url, data, success, error) {
            // Wrap the error callback to match return data between jQuery and easyXDM
            var wrappedErrorCallback = function(response){
                if('undefined' !== typeof error) {
                    error(response.responseText, response.status);
                }
            };
            $.post(url, data, success).error(wrappedErrorCallback);
        },

        /**
         * Shorcut get method.
         *
         * @param string url The url of the page to get.
         * @param object data The query data.
         * @param function success Optional callback function to use in case of succes.
         * @param function error Optional callback function to use in case of error.
         */
        get: function(url, data, success, error) {
            // Wrap the error callback to match return data between jQuery and easyXDM
            var wrappedErrorCallback = function(response){
                if('undefined' !== typeof error) {
                    error(response.responseText, response.status);
                }
            };
            $.get(url, data, success).error(wrappedErrorCallback);
        },

        /**
         * Gets the comments of a thread and places them in the thread holder.
         *
         * @param string identifier Unique identifier url for the thread comments.
         * @param string url Optional url for the thread. Defaults to current location.
         */
        getThreadComments: function(identifier, permalink) {
            if('undefined' == typeof permalink) {
                permalink = window.location.href;
            }

            RJM_COMMENT.get(
                RJM_COMMENT.base_url  + '/' + encodeURIComponent(identifier) + '/comments',
                {permalink: encodeURIComponent(permalink)},
                function(data) {
                    RJM_COMMENT.thread_container.html(data);
                    RJM_COMMENT.thread_container.attr('data-thread', identifier);
                }
            );
        },

        /**
         * Initialize the event listeners.
         */
        initializeListeners: function() {
            RJM_COMMENT.authentication.modal({show: false});

//            $('#sign-in-her-form_comment').submit(function(event) {
//                event.preventDefault();
//
//                var authForm = $(event.target);
//                $.post(authForm.attr('action'), authForm.serialize(), RJM_COMMENT.onAuthorised);
//            });

            RJM_COMMENT.thread_container.on('submit',
                'form.rjm_comment_comment_new_form',
                function(e, callback) {
                    var that = $(this);

                    RJM_COMMENT.post(
                        this.action,
                        RJM_COMMENT.serializeObject(this),
                        // success
                        function(data, statusCode) {
                            if (data.authorised === false) {
                                that.prepend($('<div class="alert alert-error">You must connect to an account or specify your name and email address</div>'));
                            } else {
                                RJM_COMMENT.appendComment(data, that);

                                if (typeof callback === 'function') {
                                    callback();
                                }
                            }
                        },
                        // error
                        function(data, statusCode) {
                            var parent = that.parent();
                            parent.after(data);
                            parent.remove();

                            if (typeof callback === 'function') {
                                callback();
                            }
                        }
                    );

                    e.preventDefault();
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_comment_reply_show_form',
                function(e) {
                    var form_data = $(this).data();
                    var that = this;

                    RJM_COMMENT.get(
                        form_data.url,
                        {parentId: form_data.parentId},
                        function(data) {
                            $(that).parent().addClass('rjm_comment_replying');
                            $(that).after(data);
                        }
                    );
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_comment_reply_cancel',
                function(e) {
                    var form_holder = $(this).parent().parent().parent();
                    form_holder.parent().removeClass('rjm_comment_replying');
                    form_holder.remove();
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_comment_edit_show_form',
                function(e) {
                    var form_data = $(this).data();
                    var that = this;

                    RJM_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            var commentBody = $(that).parent().parent().next();

                            // save the old comment for the cancel function
                            commentBody.data('original', commentBody.html());

                            // show the edit form
                            commentBody.html(data);

                            // hide reply button
                            commentBody.next().toggle();
                        }
                    );
                }
            );

            RJM_COMMENT.thread_container.on('submit',
                'form.rjm_comment_comment_edit_form',
                function(e) {
                    var that = $(this);

                    RJM_COMMENT.post(
                        this.action,
                        RJM_COMMENT.serializeObject(this),
                        // success
                        function(data) {
                            // show reply button
                            that.parent().parent().next().toggle();

                            RJM_COMMENT.editComment(data);
                        },

                        // error
                        function(data, statusCode) {
                            var parent = that.parent();
                            parent.after(data);
                            parent.parent().next().toggle();
                            parent.remove();
                        }
                    );

                    e.preventDefault();
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_comment_edit_cancel',
                function(e) {
                    RJM_COMMENT.cancelEditComment($(this).parents('.rjm_comment_comment_body'));
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_comment_vote:not(.disabled)',
                function(e) {
                    var form_data = $(this).data();
                    // Get the form
                    RJM_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            // Post it
                            var form = $(data).children('form')[0];
                            var form_data = $(form).data();

                            RJM_COMMENT.post(
                                form.action,
                                RJM_COMMENT.serializeObject(form),
                                function(data) {
                                    $('#' + form_data.scoreHolder).html(data);
                                }
                            );
                        }
                    );
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_comment_remove',
                function(e) {
                    var form_data = $(this).data();

                    // Get the form
                    RJM_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            // Post it
                            var form = $(data).children('form')[0];

                            RJM_COMMENT.post(
                                form.action,
                                RJM_COMMENT.serializeObject(form),
                                function(data) {
                                    var commentHtml = $(data);

                                    var originalComment = $('#' + commentHtml.attr('id'));

                                    originalComment.replaceWith(commentHtml);
                                }
                            );
                        }
                    );
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_thread_commentable_action',
                function(e) {
                    var form_data = $(this).data();

                    // Get the form
                    RJM_COMMENT.get(
                        form_data.url,
                        {},
                        function(data) {
                            // Post it
                            var form = $(data).children('form')[0];

                            RJM_COMMENT.post(
                                form.action,
                                RJM_COMMENT.serializeObject(form),
                                function(data) {
                                    var form = $(data).children('form')[0];
                                    var threadId = $(form).data().rjmCommentThreadId;

                                    // reload the intire thread
                                    RJM_COMMENT.getThreadComments(threadId);
                                }
                            );
                        }
                    );
                }
            );


            // Connect buttons...

            // Her dialog
            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_connect_to_her',
                function(e) {
                    e.preventDefault();
                    var that = $(this);
                    that.closest('form').css({position: 'relative'}).prepend($('<div class="rjm_comment_loader"></div>'));
                    RJM_COMMENT.modal = $('#comment-auth').modal({show: true}).on('hidden', function() {
                        $('.rjm_comment_loader').remove();
                    });
                    RJM_COMMENT.modal.find('form').on('submit', function(e) {
                        var authForm = $(e.target);
                        e.preventDefault();

                        $.post(authForm.attr('action'), authForm.serialize(), function(data) {
                            RJM_COMMENT.modal.modal('hide');

                            console.log('login form submitted...');
                            console.log(data);

                            if (data.authenticated) {
                                RJM_COMMENT.refreshCsrf();

                                // Reload the form with new CSRF token
                                var commentBody = that.closest('.rjm_comment_submit').siblings('textarea').val();
                                RJM_COMMENT.getNewCommentForm(that.data('parent-id'), commentBody, function(data) {
                                    var parent = that.closest('form').parent();
                                    parent.after($(data));
                                    parent.parent().find('textarea').val(commentBody);
                                    parent.remove();
                                });
                            }
                        });
                    });
                }
            );

            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_connect_to_facebook',
                function(e) {
                    e.preventDefault();
                    var that = $(this);
                    that.closest('form').css({position: 'relative'}).prepend($('<div class="rjm_comment_loader"></div>'));
                    window.fb_callback = function(data) {
                        if (data.authenticated) {
                            RJM_COMMENT.refreshCsrf();

                            // Reload the form with new CSRF token
                            var commentBody = that.closest('.rjm_comment_submit').siblings('textarea').val();
                            RJM_COMMENT.getNewCommentForm(that.data('parent-id'), commentBody, function(data) {
                                var parent = that.closest('form').parent();
                                parent.after($(data));
                                parent.parent().find('textarea').val(commentBody);
                                parent.remove();
                            });
                        }
                    }
                    FB.login(function(response) {
                        if (!response.authResponse) {
                            $('.rjm_comment_loader').remove();
                        }
                    });
                }
            );
            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_connect_to_twitter',
                function(e) {
                    e.preventDefault();
                    var that = $(this);
                    that.closest('form').css({position: 'relative'}).prepend($('<div class="rjm_comment_loader"></div>'));
                    window.twitter_callback = function(data) {
                        if (data.authenticated) {
                            RJM_COMMENT.refreshCsrf();

                            // Reload the form with new CSRF token
                            var commentBody = that.closest('.rjm_comment_submit').siblings('textarea').val();
                            RJM_COMMENT.getNewCommentForm(that.data('parent-id'), commentBody, function(data) {
                                var parent = that.closest('form').parent();
                                parent.after($(data));
                                parent.parent().find('textarea').val(commentBody);
                                parent.remove();
                            });
                        }
                    }

                    twttr.anywhere(function(T) {
                        T.signIn();
                    });
                }
            );


            RJM_COMMENT.thread_container.on('click',
                '.rjm_comment_not_you',
                function(e) {
                    e.preventDefault();
                    var that = $(this),
                        commentBody = that.closest('.rjm_comment_submit').siblings('textarea').val();

                    that.closest('form').css({position: 'relative'}).prepend($('<div class="rjm_comment_loader"></div>'));
                    FB.logout();

                    $.get(Routing.generate('fos_user_security_logout'), function() {
                        RJM_COMMENT.refreshCsrf();

                        RJM_COMMENT.getNewCommentForm(that.data('parent-id'), commentBody, function(data) {
                            var parent = that.closest('form').parent();
                            parent.after($(data));
                            parent.parent().find('textarea').val(commentBody);
                            parent.remove();
                        });
                    });
                }
            );


        },

        getNewCommentForm: function(parentId, body, callback) {
            var getData = (parentId !== 0) ? {parentId: parentId} : {};
            $.get(Routing.generate('rjm_comment_new_thread_comments', {id: $('#rjm_comment_thread').data('thread-id')}),
                getData,
                callback
            );
        },

        /**
         * Initiate/open the login modal window
         */
        authorise: function(callback) {
            callback = (typeof callback === 'function') ? callback : $.noop;

            window.fb_callback = window.twitter_callback = function(authenticated){
                callback(authenticated);
            };

            RJM_COMMENT.authentication.modal('show');
        },

        refreshCsrf: function() {
            $.get(Routing.generate('sonata_user_login_form', {formId: 'sign-in-her-form_comment'}), function(data) {
                $('#sign-in-her-form_comment').replaceWith(data);
            })
        },

        onAuthorised: function(data) {
//            console.log('in onAuthorised callback...');

            if(data.authenticated) {
//                console.log('authenticated, so add comment...');

                RJM_COMMENT.authentication.modal('hide');
//                bootbox.hideAll();

                // Reload the form with correct csrf token now that user is authenticated
                $('form.rjm_comment_comment_new_form').trigger('submit', [function() {
                    // Submit again with correct csrf token...
                    $('form.rjm_comment_comment_new_form').trigger('submit', [function() {
                        $('form.rjm_comment_comment_new_form').parent().parent().show();
                    }]);
                }]).parent().parent().hide();
            }
        },

        appendComment: function(commentHtml, form) {
            var form_data = form.data();

            if('' != form_data.parent) {
                var form_parent = form.parent();

                // reply button holder
                var reply_button_holder = form_parent.parent();
                reply_button_holder.removeClass('rjm_comment_replying');

                reply_button_holder.after(commentHtml);

                // Remove the form
                form_parent.remove();
            } else {
                // Insert the comment
                form.after(commentHtml);

                // "reset" the form
                form = $(form[0]);
                form.find('textarea').val('');
                form.children('.rjm_comment_form_errors').remove();
            }
        },

        editComment: function(commentHtml) {
            var commentHtml = $(commentHtml);
            var originalCommentBody = $('#' + commentHtml.attr('id')).children('.rjm_comment_comment_body');

            originalCommentBody.html(commentHtml.children('.rjm_comment_comment_body').html());
        },

        cancelEditComment: function(commentBody) {
            commentBody.html(commentBody.data('original'));
            commentBody.next().toggle();
        },

        /**
         * easyXdm doesn't seem to pick up 'normal' serialized forms yet in the
         * data property, so use this for now.
         * http://stackoverflow.com/questions/1184624/serialize-form-to-json-with-jquery#1186309
         */
        serializeObject: function(obj)
        {
            var o = {};
            var a = $(obj).serializeArray();
            $.each(a, function() {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        },

        loadCommentCounts: function()
        {
            var threadIds = [];
            var commentCountElements = $('span.rjm-comment-count');

            commentCountElements.each(function(i, elem){
                var threadId = $(elem).data('rjmCommentThreadId');
                if(threadId) {
                    threadIds.push(threadId);
                }
            });

            RJM_COMMENT.get(
                RJM_COMMENT.base_url + '.json',
                {ids: threadIds},
                function(data) {
                    // easyXdm doesn't always serialize
                    if (typeof data != "object") {
                        data = jQuery.parseJSON(data);
                    }

                    var threadData = {};

                    for (var i in data.threads) {
                        threadData[data.threads[i].id] = data.threads[i];
                    }

                    $.each(commentCountElements, function(){
                        var threadId = $(this).data('rjmCommentThreadId');
                        if(threadId) {
                            RJM_COMMENT.setCommentCount(this, threadData[threadId]);
                        }
                    });
                }
            );

        },

        setCommentCount: function(elem, threadObject) {
            if (threadObject == undefined) {
                elem.innerHTML = '0';

                return;
            }

            elem.innerHTML = threadObject.num_comments;
        }
    };

    // Check if a thread container was configured. If not, use default.
    RJM_COMMENT.authentication = window.rjm_comment_authentication || $('#comment-auth');

    // Check if a thread container was configured. If not, use default.
    RJM_COMMENT.thread_container = window.rjm_comment_thread_container || $('#rjm_comment_thread');

    // Check if a thread container was configured. If not, use default.
    RJM_COMMENT.modal = window.rjm_comment_modal || $('#comment-auth');

    // AJAX via easyXDM if this is configured
    if(typeof window.rjm_comment_remote_cors_url != "undefined") {
        /**
         * easyXDM instance to use
         */
        RJM_COMMENT.easyXDM = easyXDM.noConflict('RJM_COMMENT');

        /**
         * Shorcut request method.
         *
         * @param string method The request method to use.
         * @param string url The url of the page to request.
         * @param object data The data parameters.
         * @param function success Optional callback function to use in case of succes.
         * @param function error Optional callback function to use in case of error.
         */
        RJM_COMMENT.request = function(method, url, data, success, error) {
            // wrap the callbacks to match the callback parameters of jQuery
            var wrappedSuccessCallback = function(response){
                if('undefined' !== typeof success) {
                    success(response.data, response.status);
                }
            };
            var wrappedErrorCallback = function(response){
                if('undefined' !== typeof error) {
                    error(response.data.data, response.data.status);
                }
            };

            // todo: is there a better way to do this?
            RJM_COMMENT.xhr.request({
                    url: url,
                    method: method,
                    data: data
            }, wrappedSuccessCallback, wrappedErrorCallback);
        };

        RJM_COMMENT.post = function(url, data, success, error) {
            this.request('POST', url, data, success, error);
        };

        RJM_COMMENT.get= function(url, data, success, error) {
            // make data serialization equals to that of jquery
            var params = jQuery.param(data);
            url += '' != params ? '?' + params : '';

            this.request('GET', url, undefined, success, error);
        };

        /* Initialize xhr object to do cross-domain requests. */
        RJM_COMMENT.xhr = new RJM_COMMENT.easyXDM.Rpc({
                remote: window.rjm_comment_remote_cors_url
        }, {
            remote: {
                request: {} // request is exposed by /cors/
            }
        });
    }

    // set the appropriate base url
    RJM_COMMENT.base_url = window.rjm_comment_thread_api_base_url;


    // Load the comment if there is a thread id defined.
    if(typeof window.rjm_comment_thread_id != "undefined") {
        // get the thread comments and init listeners
        RJM_COMMENT.getThreadComments(window.rjm_comment_thread_id);
    }

    if(typeof window.rjm_comment_thread_comment_count_callback != "undefined") {
        RJM_COMMENT.setCommentCount = window.rjm_comment_thread_comment_count_callback;
    }

    if($('span.rjm-comment-count').length > 0) {
        RJM_COMMENT.loadCommentCounts();
    }

    RJM_COMMENT.initializeListeners();

    window.rjm = window.rjm || {};
    window.rjm.Comment = RJM_COMMENT;
})(window, window.jQuery, window.easyXDM);
