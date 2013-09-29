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
    public function action_songplay($songs = 0, $iflog = false) {
        $query = "select
            s.id as song_id, s.name as song_name
            from song s
            where s.id in ( $songs )
            order by field ( s.id, $songs )
            ";

        $song_array = array();
        $rows = Database::query($query);
        foreach ($rows as $row) {
            $song = array();
            $song["songid"] = $row->song_id;
            $song["title"] = $row->song_name;
            $song["mp3"] = "/m/music/song/{$row->song_id}/$iflog";
            array_push($song_array, $song);
        }

        $this->response->type('application/json');
        return json_encode($song_array);
    }

    public function action_showlyric($id=0)
    {
        $query = "select lyric from song where id = ?";
        $lyric = Database::column($query, array($id));
        if (! empty($lyric)) {
            return $lyric;
        } else {
            return "Sorry, Lyric not found.";
        }
    }
}

?>
