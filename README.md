Redirects
=========

Simple redirect module for Flow. Please keep in mind that database driven
redirects are not as fast a static rewrite rules in your webserver
configuration.

Currently it is possible to store a redirect, and resolving is first
done on the full request path including query string, and if none is
found a redirect on the path is resolved.