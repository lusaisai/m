<?php

namespace app\controllers;

use \mako\View;
use mako\Request;
use mako\Database;

class Home extends \mako\Controller
{
	public function action_index()
	{
		$data = array('overallTopSongs' => $this->overallTopSongs() );
		return new View( 'home.index', $data );
	}

	public function action_random()
	{
		$song = new Song( $this->request, $this->response );
		$songids = $song->searchSongs();
		shuffle($songids);
		$numReq = min( count($songids), 15 );
		$output = array_slice($songids, 0, $numReq);
		$randomSongs = implode(",", $output);

		return Request::factory("playutils/songplay/{$randomSongs}/0")->execute();
	}

	public function overallTopSongs()
	{
		$query = "select l.song_id, s.name as song_name, count(*) as cnt
		from playlogs l
		join song s
		on   l.song_id = s.id
		group by 1,2
		order by cnt desc
		limit 15
		";
		return Database::query($query);
	}
}
