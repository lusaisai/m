<?php

namespace app\controllers;
use mako\Database;
/**
 * Description of PlayUtils
 *
 * @author lusaisai
 */
class Playutils extends \mako\Controller {

    public function action_randomplay() {
        $query = "select
            s.name as song_name, al.name as album_name, ar.name as artist_name
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            order by rand()
            limit 20
            ";
        $this->response->type('application/json');
        return $this->songJson( Database::query($query) );
    }

    public function action_albumplay($id) {
        $query = "select
            s.name as song_name, al.name as album_name, ar.name as artist_name
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            where al.id = $id
            ";
        $this->response->type('application/json');
        return $this->songJson( Database::query($query) );
    }

    public function action_songplay($songs) {
        $query = "select
            s.name as song_name, al.name as album_name, ar.name as artist_name
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            where s.id in ( $songs )
            ";
        $this->response->type('application/json');
        return $this->songJson( Database::query($query) );
    }

    private function songJson($rows) {
        $song_array = array();

        foreach ($rows as $row) {
            $object = "{ \"title\": \"{$row->song_name}\", \"mp3\": \"/music/{$row->artist_name}/{$row->album_name}/{$row->song_name}\" }";
            array_push($song_array, $object);
        }

        $song_array_string = implode(",", $song_array);
        return "[{$song_array_string}]";
    }
}

?>
