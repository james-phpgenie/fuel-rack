Rack
====

**Version:** v0.1.1

Rack is a FuelPHP package to allow you to interact with Rackspaces api.

### Developer Notes

The `execute` function from the core `request_curl` driver has been extended.  This because the function was throwing an exception when the body was false.  When authenticating with Rackspace the reponse has a body of false but the headers returned contain all the necessry information.

### TODO:
* Container Operations
	*	Create/Update, Delete
	* Retrieve Container Metadata
	* Create/Update Container Metadata
* Object Operations
	* Copy
	* Retrieve Object Metadata
	* Update Object Metadata
* CDN-Enabled Containers Operations
	* CDN-Enable a Container
	* List CDN-Enabled Container's Metadata
	* Purge CDN-Enabled Containers
	* Update CDN-Enabled Container Metadata
* CDN Object Services
	* Purge CDN-Enabled Objects

### Done:
* Authentication
* Container Operations
	*	List Containers and Serialized List Output
	* List Objects in a Container and Serialized List Output
* Object Operations
	* Create/Update Object
	* Retrieve Object
	* Delete Object
* CDN-Enabled Containers Operations
	* List Containers

