Rack
====

**Version:** v0.0.9

Rack is a FuelPHP package to allow you to interact with Rackspaces api.

### Developer Notes

The `execute` function from the core `request_curl` driver has been extended.  This because the function was throwing an exception when the body was false.  When authenticating with Rackspace the reponse has a body of false but the headers returned contain all the necessry information.

### TODO:
* Container Operations, POST, PUT, DELETE
* Object Operations
* CDN-Enabled Containers Operations