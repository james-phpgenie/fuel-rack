<?php
/**
 * Rack is a package used to interact with
 * the Rackspace Cloud Files API
 *
 * @package    Rack
 * @version    0.0.1
 * @author     James Pudney james@phpgenie.co.uk
 * @license    See LICENCE.md
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
	 * auth-token
	 * This is retrieved from rackspace and we need it for most requests.
	 * The token should last 24 hours so it makes sense to store it.
	 **/
	 'auth-token' => '',
	 
);

