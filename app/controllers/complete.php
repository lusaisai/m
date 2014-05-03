<?php 

namespace app\controllers;

use \mako\View;
use mako\Request;
use mako\Database;
use mako\Session;
use mako\Config;


class Complete extends \mako\Controller
{

	public function before()
	{
		$this->isCache = Config::get('music.use_cache');
		// $this->isCache = false;
		$this->response->type('application/json');
	}

	public function action_artist()
	{
		if ($this->isCache) {
			$result = $this->get_cache();
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
        if ($this->isCache) $this->store_cache($result);
        return $result;
	}

	public function action_album()
	{
		if ($this->isCache) {
			$result = $this->get_cache();
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
		
        if ($this->isCache) $this->store_cache($result);
        return $result;
	}

	public function action_song()
	{
		if ($this->isCache) {
			$result = $this->get_cache();
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
		
        if ($this->isCache) $this->store_cache($result);
        return $result;
	}

	private function get_cache()
	{
		$key = DefaultCache::hashRequest($this->request->controller(), $this->request->action(), $_GET);
		return \mako\Cache::read($key);
	}

	private function store_cache($value)
	{
		$key = DefaultCache::hashRequest($this->request->controller(), $this->request->action(), $_GET);
		return \mako\Cache::write($key, $value);
	}





}

 ?>