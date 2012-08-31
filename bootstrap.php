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

Autoloader::add_core_namespace('Rack', true);

Autoloader::add_classer(
	array(
		'Rack\\Rack'	=> __DIR__.'/classes/rack.php',
	)		
);