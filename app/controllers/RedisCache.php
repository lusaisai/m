<?php 

namespace app\controllers;


use \mako\Session;

class RedisCache implements Cache
{
	const REDIS_SERVER = '127.0.0.1';
	const REDIS_ENTRY_PREFIX = 'page_cache:';

	public static function hashRequest($controller, $action, $queries)
	{
		$text_string = $controller . $action . static::array_to_string($queries);
		return hash('md5', $text_string);
	}

	public function find_cache( $controller, $action, $queries)
	{

		$page_key = static::hashRequest($controller, $action, $queries);
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
			$entry = static::REDIS_ENTRY_PREFIX . Session::get('userid', ''); 
        	return $redis->hGet( $entry, $page_key );
		} catch (Exception $e) {
			return false;
		}
		
	}

	public function store_cache($controller, $action, $queries, $view)
	{
		
		$page_key = static::hashRequest($controller, $action, $queries);
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

	public function clear_cache()
	{
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
			$entries = static::REDIS_ENTRY_PREFIX . '*'; 
			$allKeys = $redis->keys($entries);
			array_push($allKeys, Complete::REDIS_ENTRY);
			$redis->delete($allKeys);
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