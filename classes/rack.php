<?php
/**
 * Rack is a package used to interact with
 * the Rackspace Cloud Files API
 *
 * @package    Rack
 * @version    v0.2.1
 * @author     James Pudney james@phpgenie.co.uk
 * @license    See LICENCE.md
 **/
 
namespace Rack;

/**
 * Exception for a missing url for a request.
 **/
class MissingURLException extends \FuelException {}

class Rack
{
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
	 * @var string The storage url to use
	 **/
	protected static $storage_url = '';
	
	/**
	 * @var string The url used for managing the cdn.
	 **/
	protected static $cdn_management = '';
	
	private function __construct() { }
	
	/**
	 * A static constructor that's called by the 
	 * autoloader.
	 **/
	public static function _init()
	{
		
		// load the configuration
		$config = \Config::load('rack');
		$last_saved = \Config::get('last-saved');
		
		// before we can do anything we need to check and see if we have a 
		// an api url
		if (empty($config['api-url'])) {
			
			throw new \FuelException('No api url given.');
			
		} else {
			
			static::$api_url = \Config::get('api-url');
			
		}
		
		// check for an auth token, if we don't have it 
		// then check for the auth user and key
		if (empty($config['auth-token'])) {
			
			// we have no key!
			if (empty($config['auth-user']) || (empty($config['auth-user']))) {
				
				// haven't got anything to go on, best throw an error!
				throw new \FuelException('No auth-token found, and no details were provided to retrieve one.  Please provide an auth-user and auth-key or an auth-token.');
				
			} else {
				
				static::$auth_user = \Config::get('auth-user');
				static::$auth_key = \Config::get('auth-key');
				
				static::$auth_token = static::get_auth_token();
				
			}
			
		} else {
			
			static::$auth_user = \Config::get('auth-user');
			static::$auth_key = \Config::get('auth-key');
			
			// check whether we need a new auth token
			if ((\Date::forge()->get_timestamp() - $last_saved) > 86400) {
				
				static::$auth_token = static::get_auth_token();
				
			} else {
				
				static::$auth_token = \Config::get('auth-token');
				
			}
			
		}
		
		static::$storage_url = \Config::get('storage-url');
		static::$cdn_management = \Config::get('cdn-management');
	}
	
	/**
	 * A private function for checking whether the auth token
	 * is still valid or whether it has expired.
	 *
	 * @return	boolean The outcome of the check
	 **/
	private static function is_auth_token_valid()
	{
		// check whether the auth_token is valid
		// if a 401 is returned then we know it's not valid
		
		$auth_user = static::$auth_user;
		$auth_key = static::$auth_key;
		$api_url = static::$api_url;
		
		$headers = array(
			'X-Auth-Key: '.$auth_key,
			'X-Auth-User: '.$auth_user,
		);
		
		$options = array(
			'HEADER' => true, // we want to include the header in the ouput
			'HTTPHEADER' => $headers,
			'RETURNTRANSFER' => true,
		);
		
		$request = \Request::forge($api_url, 
			array(
				'driver' => 'curl', 
				'options' => $options
			), 
			'GET'
		);
		
		$response = $request->execute()->response();
		
		if ($response->headers['X-Auth-Token'] === \Config::get('auth-token')) {
			// we have a match
			return true;
			
		}
		
		return false;
	}
	
	/**
	 * Authenticates using the user credentials.
	 * 
	 * @return	string	auth_token Returns the authorisation token.
	 **/
	private static function get_auth_token()
	{
		$auth_user = static::$auth_user;
		$auth_key = static::$auth_key;
		$api_url = static::$api_url;
		
		$headers = array(
			'X-Auth-Key: '.$auth_key,
			'X-Auth-User: '.$auth_user,
		);
		
		$options = array(
			'HEADER' => true, // we want to include the header in the ouput
			'HTTPHEADER' => $headers,
			'RETURNTRANSFER' => true,
		);
		
		$response = static::request($headers, $options, $api_url, 'GET');
		
		$auth_token = '';
		$storage_url = '';
		$cdn_management = '';
		
		if ($response->status == 204 || $response->status == 202) {
			// all's good in the hood
			
			static::$storage_url = $response->headers['X-Storage-Url'];
			static::$cdn_management = $response->headers['X-CDN-Management-Url'];
			static::$auth_token = $response->headers['X-Auth-Token'];
			
			static::save_details_to_config();
			
			return $response->headers['X-Auth-Token'];
		
		} else {
			
			throw new \FuelException('Please chack the auth-key and auth-user supplied.');
			
		}
	}
	
	/**
	 * The request function offers a helper method for the curl request.
	 * 
	 * @param	array		headers	The headers to be used for the curl request
	 * @param	array		options	The options to be used for the curl request
	 * @param	array		params	The params to be encoded in the curl request
	 * @param	string	url	The url to be used for the request
	 * @param	string	method	The method to be used for the curl request, GET, POST, PUT, etc.
	 * @return	mixed	Returns the response object
	 * @throws	MissingURLException
	 **/
	private static function request($headers = array(), $options = array(), $url = '', $method = 'GET', $params = array())
	{
		
		if ($url === '') {
			throw new MissingURLException("The url given is empty.");			
		}
		
		$options['HTTPHEADER'] = $headers;
		// $options['TIMEOUT'] = 3; // timeout 3 seconds
		
		$request = \Request::forge(
			$url, 
			array(
				'driver' => 'curl', 
				'options' => $options,
				'params' => $params
			), 
			$method
		);
		
		try {
			
			$request->execute();
			
		} catch (Exception $e) {
			
		}
		
		return $request->response();
		
	}
	
	//--------- Container Methods ----------//
	
	/**
	 * Returns a list of containers.  The maximum of 10,000 container names will be returned.  Parameters are to be passed in an array.
	 * 
	 * @param	int	limit	Limits the number of containers returned.
	 * @param	string	marker	Returns object names greater in value than the specified marker. Only strings using UTF-8 encoding are valid.
	 * @param	string	format	Specify either json or xml as the format for the returned values.  If left blank an array of strings will be returned.
	 * @return mixed The response depends on the format.  By default this should be an array of container objects encoded using json.  The objects consists of the following attributes: name, count, bytes.
	 **/
	public static function get_containers($params = array('limit' => 10000, 'marker' => '', 'format' => ''))
	{
		$headers = array(
			'X-Auth-Token: '.static::$auth_token
		);
		
		$response = static::request($headers, array(), static::$storage_url, 'GET', $params);
		
		$body = $response->body();
		
		return ($params['format'] === '' ? explode("\n", $body, -1) : $body);		
		
	}
	
	/**
	 * Returns a list of objects for a container.  Parameters are to be passed in an array.
	 *
	 * @return	mixed	The response body is returned.  The format of which depends on the format given.  If none then the reponse is json encoded by default.
	 **/
	public static function get_objects_list($container = '', $params = array('limit' => 10000, 'marker' => '', 'prefix' => '', 'format' => '', 'path' => '', 'deliminator' => ''))
	{
		$headers = array(
			'X-Auth-Token: '.static::$auth_token
		);
				
		$url = static::$storage_url.'/'.$container;
		
		$response = static::request($headers, array(), $url, 'GET', $params);
		
		$body = $response->body();
		
		return ($params['format'] === '' ? explode("\n", $body, -1) : $body);
	}
	
		
	//----- Storage Object Methods - GET, PUT, DELETE, UPDATE -----//
	
	/**
	 * get_object allows you to retrieve an object from a container.
	 * 
	 * @param	string	container	The name of the container where the object is being stored
	 * @param	string	object	The name of the object to retrieve
	 * @param	array	conditions	Headers for conditional Get Requests. If-Match, If-None-Match, etc.
	 *
	 * @return void
	 * @author James Pudney
	 **/
	public static function get_object($container = '', $object = '', $conditions = array())
	{
		$headers = array(
			'X-Auth-Token: '.static::$auth_token
		);
		
		$params = array();
		
		foreach ($conditions as $key => $value) {
			$param[$key] = $value;
		}
		
		$url = static::$storage_url.'/'.$container.'/'.$object;
		
		$response = static::request($headers, array(), $url, 'GET', $params);
		
		return $response->body();	
	}
	
	/**
	 * put_object allows the creation or updating of an object.
	 * 
	 * @param string container The name of the container where the object is to be created.
	 * @param string file The location of the file.
	 *
	 * @return void
	 * @author James Pudney
	 **/
	public static function put_object($container = '', $file = null)
	{
		if ($file === null) {
			throw new \FuelException("File is null");			
		}
		
		if ($container === '') {
			throw new \FuelException("Container name empty");			
		}
		
		$file_info = \File::file_info($file);
		
		$md5_file = md5_file($file);
		
		$url = static::$storage_url.'/'.$container.'/'.$file_info['basename'];
		
		$headers = array(
			'X-Auth-Token: '.static::$auth_token,
			'Content-Type: '.$file_info['mimetype'], 
			'ETag: '.$md5_file,
		);
		
		$options = array(
			'HTTPHEADER' => $headers, 
			'PUT' => 1, 
			'INFILE' => fopen($file, 'rb'), 
			'INFILESIZE' => filesize($file),
		);
		
		$response = static::request($headers, $options, $url, 'PUT');
		
		$callCount = 0;
		
		if ($response->status == 401 && $callCount == 0) {
			
			// we' not authorised, try getting a new auth-token and try again
			static::$auth_token = static::get_auth_token();
			static::put_object($container, $file);
			
			$callCount++;
			
		} elseif ($response->status == 401 && $callCount > 0) {
			
			throw new \FuelException("We weren't authorised to place the object in the container.  We've already tried getting a new auth-token so this issue could be something else.  Try checking the container name is correct.");
			
		}
		
		return $response;
		
	}
	
	/**
	 * Deletes the given object from the given container, and returns the response.  The function can be passed delete_at or delete_after 
	 * variables to delete the file at a particular time or after a particular number of seconds.
	 * 
	 * @param string	container	The name of the container where the object resides
	 * @param	string	object	The name of the object to be deleted
	 * @param	int	delete_at	A Unix Epoch timestamp, in integer form
	 * @param	int	delete_after	The number of seconds to pass before deletion occurs.
	 *
	 * @return mixed response The response object returned
	 * @author James Pudney
	 **/
	public static function delete_object($container = '', $object = '', $delete_at = 0, $delete_after = 0)
	{
		if ($container === '') {
			throw new \FuelException("No container name given");			
		}
		
		if ($object === '') {
			throw new \FuelException("No object name given");			
		}
		
		$headers = array(
			'X-Auth-Token: '.static::$auth_token,
		);
		
		if ($delete_at != 0) {
			$headers[] = 'X-Delete-At: '.$delete_at;
		}
		
		if ($delete_after != 0) {
			$headers[] = 'X-Delete-After: '.$delete_after;
		}
		
		$url = static::$storage_url.'/'.$container.'/'.$object;
		
		return static::request($headers, array(), $url, 'DELETE', array());
	}
		
	//----- CDN Operations Methods ------//
	
	/**
	 * Returns an array of CDN enabled containers
	 * 
	 * @param int	limit	For an integer value n, limits the number of results to n values
	 * @param string	marker	Given a string value x, return object names greater in value than the specified marker. Only strings using UTF-8 encoding are valid
	 * @param string	format	The format of the response, xml or json.  json by default
	 * @param boolean	enabled_only	If true returns only enabled containers
	 **/
	public static function get_cdn_containers($limit = 10000, $marker = '', $format = 'json', $enabled_only = false)
	{
		$headers = array(
			'X-Auth-Token: '.static::$auth_token
		);
		
		$params = array(
			'limit' => $limit,
			'marker' => $marker,
			'format' => $format,
			'enabled_only' => $enabled_only,
		);
		
		$response = static::request($headers, array(), static::$cdn_management, 'GET', $params);
		
		return $response->body();		
	}
	
	/**
	 * Returns meta data in an array for a given container
	 *
	 * @param string container The name of the container to retrieve meta data 
	 * @return mixed
	 **/
	public static function head_cdn_container_meta($container = '')
	{
		$headers = array(
			'X-Auth-Token: '.static::$auth_token,
		);
		
		$options = array(
			'HEADER' => true, // we want to include the header in the ouput
		);
		
		$url = static::$cdn_management.'/'.$container;
		
		$response = static::request($headers, $options, $url, 'HEAD', array());
		
		return $response;
	}
	
	//------ General operations ------//
	
	/**
	 * Saves the auth details on urls to the applications config dir, if it's writeable
	 **/
	private static function save_details_to_config()
	{
		\Config::load('rack');
		
		\Config::set('rack', 
			array(
				'auth-user' => static::$auth_user,
				'auth-key' => static::$auth_key,
				'api-url' => static::$api_url,
				'auth-token' => static::$auth_token,
				'storage-url' => static::$storage_url,
				'cdn-management' => static::$cdn_management,
				'last-saved' => \Date::forge()->get_timestamp(),				
			)
		);
		
		\Config::save('rack', 'rack');
	}
}
