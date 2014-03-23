<?php 

namespace app\controllers;

use \mako\View;
use mako\Request;
use mako\Database;
use mako\Session;

class Complete extends \mako\Controller
{
	public function action_artist()
	{
		$artist = new Artist( $this->request, $this->response );
		$ids = implode(',', $artist->searchArtists());
		$query = "select GROUP_CONCAT(name) as names from artist where id in ( $ids ) limit 15";
		$names = Database::column($query);

		$this->response->type('application/json');
        return json_encode( array_values(array_unique(explode(',', $names))) );
	}

	public function action_album()
	{
		$album = new Album( $this->request, $this->response );
		$ids = implode(',', $album->searchAlbums());
		$query = "select GROUP_CONCAT(name) as names from album where id in ( $ids ) limit 15";
		$names = Database::column($query);

		$this->response->type('application/json');
        return json_encode( array_values(array_unique(explode(',', $names))) );
	}

	public function action_song()
	{
		$song = new Song( $this->request, $this->response );
		$ids = implode(',', $song->searchSongs());
		$query = "select GROUP_CONCAT(name) as names from song where id in ( $ids ) limit 15";
		$names = Database::column($query);

		$this->response->type('application/json');
        return json_encode( array_values( array_unique(explode(',', $names)) ) );
	}



}

 ?>