<?php 

namespace app\controllers;

/**
* To hash a request and cache the result
*/
interface Cache {
	public function find_cache($controller, $action, $queries);
	public function store_cache($controller, $action, $queries, $view);
	public function clear_cache();
}
