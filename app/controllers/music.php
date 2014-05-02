<?php

namespace app\controllers;

use \mako\Database;
use \mako\Session;
use \mako\Config;


class Music extends \mako\Controller
{
	public function action_stats($id = 0)
	{
		if ($id) {
			$this->playlog($id);
		}
	}

	private function playlog($id)
	{
		$userid = Session::get("userid", -1);
		$query = "insert into playlogs ( user_id, song_id ) values (?,?)";
		Database::query( $query, array( $userid, $id ) );
	}
}

?>
