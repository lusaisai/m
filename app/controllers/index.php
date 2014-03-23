<?php

namespace app\controllers;

use \mako\View;

class Index extends \mako\Controller
{
	public function action_index()
	{
		$redis = new \Redis();
		$redis->connect('127.0.0.1');
		$data = $redis->hget( 'page_cache', 'c937b5a982370960748652dc85ff483f' );
		echo $data;
	}
}