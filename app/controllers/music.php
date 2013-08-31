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
		$row = Database::first( $query );
		// $log = \mako\Log::instance();

		if ($row) {

			$file = "C:/wamp/www/music/{$row->artist_name}/{$row->album_name}/{$row->song_name}";
			$file = mb_convert_encoding( $file, "cp936" );
			$filesize = filesize($file);
			$offset = 0;
			$length = $filesize;

			if ( isset($_SERVER['HTTP_RANGE']) ) {
				preg_match('/bytes=(\d+)-(\d+)?/', $_SERVER['HTTP_RANGE'], $matches);
				$offset = intval($matches[0]);
			}
			if ( $offset == 0 && $iflog ) {
				$this->playlog($id);
			}

			$this->response->type('audio/mpeg');
			$this->response->header("Accept-Ranges", "bytes");
			$this->response->header("Content-Length", $filesize);
			$this->response->header("Content-Range", 'bytes ' . $offset . '-' . ($offset + $length) . '/' . $filesize);

			$file = fopen($file, 'r');
			fseek($file, $offset);
			$data = fread($file, $length);
			fclose($file);

			return $data;
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