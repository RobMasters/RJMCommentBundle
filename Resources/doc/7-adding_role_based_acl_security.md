Step 7: Adding role based ACL security
======================================

**Note:**

> This bundle ships with support different security setups. You can also have a look at [Adding Symfony2's built in ACL security](8-adding_symfony2s_builtin_acl_security.md).

CommentBundle also provides the ability to configure permissions based on the roles
a specific user has. See the configuration example below for how to customise the
default roles used for permissions.

To configure Role based security override the Acl services:

``` yaml
# app/config/config.yml

rjm_comment:
    acl: true
    service:
        acl:
            thread:  rjm_comment.acl.thread.roles
            comment: rjm_comment.acl.comment.roles
            vote:    rjm_comment.acl.vote.roles
        manager:
            thread:  rjm_comment.manager.thread.acl
            comment: rjm_comment.manager.comment.acl
            vote:    rjm_comment.manager.vote.acl
```

To change the roles required for specific actions, modify the `acl_roles` configuration
key:

``` yaml
# app/config/config.yml

rjm_comment:
    acl_roles:
        comment:
            create: IS_AUTHENTICATED_ANONYMOUSLY
            view: IS_AUTHENTICATED_ANONYMOUSLY
            edit: ROLE_ADMIN
            delete: ROLE_ADMIN
        thread:
            create: IS_AUTHENTICATED_ANONYMOUSLY
            view: IS_AUTHENTICATED_ANONYMOUSLY
            edit: ROLE_ADMIN
            delete: ROLE_ADMIN
        vote:
            create: IS_AUTHENTICATED_ANONYMOUSLY
            view: IS_AUTHENTICATED_ANONYMOUSLY
            edit: ROLE_ADMIN
            delete: ROLE_ADMIN
```

## That is it!
[Return to the index.](index.md)
