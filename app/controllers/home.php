<?php

namespace app\controllers;

use \mako\View;
use mako\Request;
use mako\Database;
use mako\Session;

class Home extends \mako\Controller
{
	public function action_index()
	{
		$data = array('topSongs' => $this->topSongs() );
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
		on   l.song_id = s.id ";
		if ($userid) {
			$query .= "where l.user_id = $userid and date(l.play_ts) >= date_sub( CURRENT_DATE, interval $backdays day )";
		} else {
			$query .= "where date(l.play_ts) >= date_sub( CURRENT_DATE, interval $backdays day )";
		}
		$query .= " group by 1,2
		order by cnt desc
		limit 15
		";
		return Database::query($query);
	}
}
