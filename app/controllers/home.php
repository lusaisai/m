<?php

namespace app\controllers;

use \mako\View;

class Home extends \mako\Controller
{
	public function action_index()
	{
		return new View('home.index');
	}

	public function action_random()
	{
		$song = new Song( $this->request, $this->response );
		$songids = $song->searchSongs();
		shuffle($songids);
		$numReq = min( count($songids), 15 );
		$output = array_slice($songids, 0, $numReq);
		$randomSongs = implode(",", $output);

		$this->response->type('application/json');
		return "{ \"ids\": \"$randomSongs\" }";
	}
}