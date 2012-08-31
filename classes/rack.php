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
	// set up vars used for curl requests
	private $headers = array();
	private $options = array();
	private $params = array();
	
	/**
	 * @var string The auth username provided by Rackspace
	 **/
	protected static $auth_user = '';
	
	/**
	 * @var string The auth key provided by Rackspace
	 **/
	protected static $auth_key = '';
	
	/**
	 * @var string The API url to be used
	 **/
	protected static $api_url = '';
	
	/**
	 * @var string The _secret_ auth token
	 **/
	protected static $auth_token = '';
	
	/**
	 * A static constructor that's called by the 
	 * autoloader.
	 **/
	public static function _init()
	{
		
		// load the configuration
		$config = \Config::load('rack');
		
		// check for an auth token, if we don't have it 
		// then check for the auth user and key
		if (empy(\Config::get('auth-token', null))) {
			
			// we have no key!
			if ((empty(\Config::get('auth-user'))) || (empty(\Config::get('auth-key')))) {
				
				// haven't got anything to go on, best throw an error!
				throw new \Fuel_Exception('No auth-token found, and no details were provided to retrieve one.  Please provide an auth-user and auth-key or an auth-token.');
				
			} else {
				
				static::$auth_user = \Config::get('auth-user');
				static::$auth_key = \Config::get('auth-key');
				
			}
			
		}
		
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
