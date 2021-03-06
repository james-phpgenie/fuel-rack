<?php
/**
 * Rack is a package used to interact with
 * the Rackspace Cloud Files API
 *
 * @package    Rack
 * @version    v0.0.1
 * @author     James Pudney james@phpgenie.co.uk
 * @license    See LICENCE.md
 **/

Autoloader::add_core_namespace('Rack');

Autoloader::add_classes(
	array(
		'Rack\\Rack'	=> __DIR__.'/classes/rack.php',
		'Request_Curl' => __DIR__.'/classes/curl.php',
	)		
);

/* End of file bootstrap.php */