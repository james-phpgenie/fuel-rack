Rack
====

**Version:** v0.3.0

Rack is a FuelPHP package to allow you to interact with Rackspaces api.

### Developer Notes

The `execute` function from the core `request_curl` driver has been extended.  This because the function was throwing an exception when the body was false.  When authenticating with Rackspace the reponse has a body of false but the headers returned contain all the necessry information.

### Changelog
v0.3.0 - Added two functions to manage containers, add and delete.  In future checking of certain parameters needs to be a bit more thorough.
v0.2.3 - When saving the retrieved details for accessing the api the date saved is now marked as midnight rather than the time accessed.  When an instance of rack is used it checks the timestamp to see if it's been 24 hours.  It does this because Rackspace states that the auth_token given will be valid for 24 hours.  So rather than retrieving a new access token we were assuming that the current token is still valid.  In future if a request fails we should check the auth_token and try again rather than relying on a timestamp.

### TODO:
* Container Operations
	* Retrieve Container Metadata
	* Create/Update Container Metadata
* Object Operations
	* Copy
	* Retrieve Object Metadata
	* Update Object Metadata
* CDN-Enabled Containers Operations
	* CDN-Enable a Container
	* Purge CDN-Enabled Containers
	* Update CDN-Enabled Container Metadata
* CDN Object Services
	* Purge CDN-Enabled Objects

### Done:
* Authentication
* Container Operations
	*	Create/Update, Delete
	*	List Containers and Serialized List Output
	* List Objects in a Container and Serialized List Output
* Object Operations
	* Create/Update Object
	* Retrieve Object
	* Delete Object
* CDN-Enabled Containers Operations
	* List Containers
	* List CDN-Enabled Container's Metadata

