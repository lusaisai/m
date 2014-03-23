<?php

namespace app\controllers;

use \mako\View;
use mako\Request;
use mako\Database;
use mako\Session;
use mako\Config;

class Home extends \mako\Controller
{
	public function action_index()
	{

		$isCache = Config::get('music.use_cache');
		if ($isCache) {
			$result = Hash::find_cache($this->request->controller(), $this->request->action(), $_GET );
			if ($result) return $result;
		}

		$data = array('topSongs' => $this->topSongs(), 'topArtists' => $this->topArtists() );
		$view = new View( 'home.index', $data );
		if ($isCache) Hash::store_cache($this->request->controller(), $this->request->action(), $_GET, $view);
		return $view;
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

	public function action_topsongdata( $user = "all", $time = "all")
	{
		if ($user == "user" && Session::get( "isLogin", false ) ) {
			$userid = Session::get( "userid", 0 );
		} else {
			$userid = 0;
		}
		$data = array('topSongs' => $this->topSongs( $userid, $time ) );
		return new View( 'home.topsongstags', $data );
	}

	public function topSongs( $userid = 0, $time = "all" )
	{
		switch ($time) {
			case 'week':
				$backdays = 7;
				break;
			case 'month':
				$backdays = 30;
				break;
			default:
				$backdays = 365 * 100;
				break;
		}
		$query = "select l.song_id, s.name as song_name, count(*) as cnt
		from playlogs l
		join song s
		on   l.song_id = s.id
		where date(l.play_ts) >= date_sub( CURRENT_DATE, interval $backdays day )
		";
		if ($userid) {
			$query .= " and l.user_id = $userid ";
		}
		$query .= " group by 1,2
		order by cnt desc
		limit 15
		";
		return Database::query($query);
	}

	public function action_topartistdata( $user = "all", $time = "all")
	{
		if ($user == "user" && Session::get( "isLogin", false ) ) {
			$userid = Session::get( "userid", 0 );
		} else {
			$userid = 0;
		}
		$data = array('topArtists' => $this->topArtists( $userid, $time ) );
		return new View( 'home.topartiststags', $data );
	}

	public function topArtists( $userid = 0, $time = "all" )
	{
		switch ($time) {
			case 'week':
				$backdays = 7;
				break;
			case 'month':
				$backdays = 30;
				break;
			default:
				$backdays = 365 * 100;
				break;
		}
		$query = "select al.artist_id, ar.name as artist_name, i.image_name, count(*) as cnt
		from playlogs l
		join song s
		on   l.song_id = s.id
		join album al
		on   s.album_id = al.id
		join artist ar
		on   al.artist_id = ar.id
		join ( select artist_id, name as image_name from image where album_id is null group by 1 order by rand() ) i
		on   ar.id = i.artist_id
		where date(l.play_ts) >= date_sub( CURRENT_DATE, interval $backdays day )
		";
		if ($userid) {
			$query .= " and l.user_id = $userid ";
		}
		$query .= " group by 1,2,3
		order by cnt desc
		limit 15
		";
		return Database::query($query);
	}
}
