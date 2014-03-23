<?php 

namespace app\controllers;

use \Redis;
use \mako\Session;

/**
* To hash a request and cache the result
*/
class Hash
{
	const REDIS_SERVER = '127.0.0.1';
	const REDIS_ENTRY_PREFIX = 'page_cache:';

	public static function hash($controller, $action, $queries)
	{
		$text_string = $controller . $action . static::array_to_string($queries);
		return hash('md5', $text_string);
	}

	public static function find_cache( $controller, $action, $queries)
	{

		$page_key = static::hash($controller, $action, $queries);
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
			$entry = static::REDIS_ENTRY_PREFIX . Session::get('userid', ''); 
        	return $redis->hGet( $entry, $page_key );
		} catch (Exception $e) {
			return false;
		}
		
	}

	public static function store_cache($controller, $action, $queries, $view)
	{
		
		$page_key = static::hash($controller, $action, $queries);
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
			$entry = static::REDIS_ENTRY_PREFIX . Session::get('userid', ''); 
			$redis->hSet( $entry , $page_key, (string)$view );
			return true;
		} catch (Exception $e) {
			return false;
		}
		
	}

	public static function clear_cache()
	{
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
			$entry = static::REDIS_ENTRY_PREFIX . Session::get('userid', ''); 
			$redis->del( $entry );
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	public static function clear_cache_all()
	{
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
			$entries = static::REDIS_ENTRY_PREFIX . '*'; 
			$allKeys = $redis->keys($entries);
			foreach ($allKeys as $key) {
				$redis->del( $key );
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
		
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

 ?>