<?php

namespace app\controllers;

use \mako\Session;

class DefaultCache implements Cache
{
	public function __construct()
	{
		$this->cache = \mako\Cache::instance();
	}

	const REDIS_SERVER = '127.0.0.1';
	const REDIS_ENTRY_PREFIX = 'page_cache:';

	public static function hashRequest($controller, $action, $queries)
	{
		$text_string = $controller . $action . static::array_to_string($queries);
		return hash('md5', $text_string);
	}

	public function find_cache( $controller, $action, $queries)
	{

		$page_key = static::hashRequest($controller, $action, $queries) . Session::get('userid', ''); 
		return $this->cache->read($page_key);		
	}

	public function store_cache($controller, $action, $queries, $view)
	{
		
		$page_key = static::hashRequest($controller, $action, $queries) . Session::get('userid', '');
		return $this->cache->write($page_key, (string)$view);
	}

	public function clear_cache()
	{
		$this->cache->clear();	
	}

	private static function array_to_string($values=array())
	{
		$single_array = array();
		foreach ($values as $key => $value) {
			array_push( $single_array, $key . $value );
		}
		sort($single_array);
		return implode( $single_array );
	}


}
