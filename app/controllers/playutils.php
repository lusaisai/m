<?php

namespace app\controllers;
use mako\Database;
use mako\Session;
/**
 * Description of PlayUtils
 *
 * @author lusaisai
 */
class Playutils extends \mako\Controller {

    public function action_randomplay() {
        $query = "select
            s.id as song_id, s.name as song_name, al.name as album_name, ar.name as artist_name, -1 as log_id
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

    public function action_songplay($songs = 0, $iflog = true) {
        $query = "select
            s.id as song_id, s.name as song_name, al.name as album_name, ar.name as artist_name, coalesce(l.log_id, 0) as log_id
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            left join ( select song_id, max(id) as log_id from playlogs group by 1 ) l
            on   s.id = l.song_id
            where s.id in ( $songs )
            order by field ( s.id, $songs )
            ";
        if ($iflog) {$this->playlog($songs);}
        $this->response->type('application/json');
        return $this->songJson( Database::query($query) );
    }

    public function action_songremove($logid = -1)
    {
        $query = "update playlogs set is_deleted = 1 where id = ?";
        Database::query( $query, array($logid) );
    }

    private function songJson($rows) {
        $song_array = array();

        foreach ($rows as $row) {
            $object = "{ \"logid\": \"{$row->log_id}\",\"songid\": \"{$row->song_id}\", \"title\": \"{$row->song_name}\", \"mp3\": \"/music/{$row->artist_name}/{$row->album_name}/{$row->song_name}\" }";
            array_push($song_array, $object);
        }

        $song_array_string = implode(",", $song_array);
        return "[{$song_array_string}]";
    }

    private function playlog($songs)
    {
        $userid = Session::get("userid", -1);
        $connection = Database::connection();
        $query = "insert into playlogs ( user_id, song_id ) values (?,?)";
        try {
            $connection->pdo->beginTransaction();
            foreach ( explode(",", $songs) as $songid ) {
                 $connection->query( $query, array($userid, $songid) );
            }
            $connection->pdo->commit();
        } catch(PDOException $e) {
            $connection->pdo->rollBack();
        }
    }
}

?>
