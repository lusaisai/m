<?php

namespace app\controllers;

use mako\Database;
use mako\Session;


class Music extends \mako\Controller
{
	public function action_song($id = 0, $iflog = true)
	{
		$query = "select
            s.id as song_id, s.name as song_name, al.name as album_name, ar.name as artist_name
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            where s.id = $id
            ";
        $this->response->type('audio/mpeg');
        $row = Database::first( $query );
        if ($row) {
        	if ($iflog) {$this->playlog($id);}
        	$file = "/var/www/music/{$row->artist_name}/{$row->album_name}/{$row->song_name}";
        	return file_get_contents($file);
        } else {
        	return "";
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