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
 
namespace Rack;
 
class Rack
{
	/**
	 * Constructor for the Rack package.
	 * Loads the config settings and set up the object.
	 **/
	public function __construct()
	{
	}
	
	/**
	 * Authenticates using the user credentials.
	 * 
	 * @param string auth_user The username of the user.
	 * @param string auth_key	The authorisation key.
	 * 
	 * @return string auth_token Returns the authorisation token.
	 **/
	public function authenticate()
	{
	}
	
	/**
	 * Returns a list of containers.  The maximum of 10,000 container names will be returned.
	 * 
	 * @param int $limit Limits the number of containers returned.
	 * @param string $marker Returns object names greater in value than the specified marker. Only strings using UTF-8 encoding are valid.
	 * @param string $format Specify either json or xml as the format for the returned values.  If left blank an array of strings will be returned.
	 *
	 * @return void
	 * @author James Pudney
	 **/
	public function get_containers($limit = null, $marker = '', $format = '')
	{
	}
}
