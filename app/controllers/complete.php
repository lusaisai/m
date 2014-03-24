<?php 

namespace app\controllers;

use \mako\View;
use mako\Request;
use mako\Database;
use mako\Session;
use mako\Config;
use \Redis;

class Complete extends \mako\Controller
{
	const REDIS_SERVER = '127.0.0.1';
	const REDIS_ENTRY = 'complete_cache:';

	public function before()
	{
		$this->isCache = Config::get('music.use_cache');
		// $this->isCache = false;
		$this->response->type('application/json');
	}

	public function action_artist()
	{
		if ($this->isCache) {
			$result = $this->get_redis_cache();
			if($result) return $result;
		}

		$result = "";
		$artist = new Artist( $this->request, $this->response );
		$ids = implode(',', $artist->searchArtists());
		if($ids) {
			$query = "select GROUP_CONCAT(name) as names from artist where id in ( $ids ) limit 15";
			$names = Database::column($query);
        	$result = json_encode( array_values(array_unique(explode(',', $names))) );
		}
        if ($this->isCache) $this->store_redis_cache($result);
        return $result;
	}

	public function action_album()
	{
		if ($this->isCache) {
			$result = $this->get_redis_cache();
			if($result) return $result;
		}

		$result = "";
		$album = new Album( $this->request, $this->response );
		$ids = implode(',', $album->searchAlbums());
		if ($ids) {
			$query = "select GROUP_CONCAT(name) as names from album where id in ( $ids ) limit 15";
			$names = Database::column($query);
        	$result = json_encode( array_values(array_unique(explode(',', $names))) );
		}
		
        if ($this->isCache) $this->store_redis_cache($result);
        return $result;
	}

	public function action_song()
	{
		if ($this->isCache) {
			$result = $this->get_redis_cache();
			if($result) return $result;
		}

		$result = "";
		$song = new Song( $this->request, $this->response );
		$ids = implode(',', $song->searchSongs());
		if ($ids) {
			$query = "select GROUP_CONCAT(name) as names from song where id in ( $ids ) limit 15";
			$names = Database::column($query);
        	$result = json_encode( array_values( array_unique(explode(',', $names)) ) );
		}
		
        if ($this->isCache) $this->store_redis_cache($result);
        return $result;
	}

	private function get_redis_cache()
	{
		$key = Hash::hash($this->request->controller(), $this->request->action(), $_GET);
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
        	return $redis->hGet( static::REDIS_ENTRY, $key );
		} catch (Exception $e) {
			return false;
		}
	}

	private function store_redis_cache($value)
	{
		$key = Hash::hash($this->request->controller(), $this->request->action(), $_GET);
		try {
			$redis = new Redis();
			$redis->connect(static::REDIS_SERVER);
        	return $redis->hSet( static::REDIS_ENTRY, $key, $value );
		} catch (Exception $e) {
			return false;
		}
	}





}

 ?>