<?php
/**
 * Rack is a package used to interact with
 * the Rackspace Cloud Files API
 *
 * @package    	Rack
 * @version			v0.1.1
 * @author     	James Pudney james@phpgenie.co.uk
 * @license    	See LICENCE.md
 **/

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 **/

return array(
	
	/**
	 * api-url
	 * This is the url used to access the Rackspace api.  By default this is 
	 * set to the UK address.
	 **/
	 'api-url' => 'https://lon.auth.api.rackspacecloud.com/v1.0',
	 
 	/**
 	 * auth-user
 	 * The username of the user for Rackspace
 	 **/
 	 'auth-user' => '',
	 
 	/**
 	 * auth-key
 	 * The key provided by Rackspace that's linked to the above username
 	 **/
 	 'auth-key' => '',
	
	/**
	 * auth-token
	 * This is retrieved from Rackspace and we need it for most requests.
	 * The token should last 24 hours so it makes sense to store it.
	 **/
	 'auth-token' => '',
	 
	 /**
	  * storage-url
	  * This is retrieved from Rackspace after authentication.  It's the url where we can store all
	  * our stuff!
	  **/
	 'storage-url' => '',
	 
	 /**
	  * cdn-managment
	  * The url used for managing the cdn.
	  **/
	 'cdn-managment' => '',
	 
	 /**
	  * last-saved
	  * We store the timestamp when we last retrieved the auth-token.  The token expires every 24 hours.
	  **/
	 'last-saved' => 0,
	 
);

