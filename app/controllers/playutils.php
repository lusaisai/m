<?php

namespace app\controllers;
use mako\Config;
use mako\Database;
use mako\Session;
use mako\File;
/**
 * Description of PlayUtils
 *
 * @author lusaisai
 */
class Playutils extends \mako\Controller {
    public function action_songplay($songs = 0, $iflog = false) {
        $query = "select
            s.id as song_id, s.name as song_name, s.file_name, al.name as album_name, ar.name as artist_name
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            where s.id in ( $songs )
            order by field ( s.id, $songs )
            ";
        $musicDir = Config::get( "music.dir" );
        $musicUrlPre = Config::get( "music.url" );
        

        $song_array = array();
        $rows = Database::all($query);
        foreach ($rows as $row) {
            $song = array();
            $song["songid"] = $row->song_id;
            $song["title"] = $row->song_name;
            $song["song_info"] = "From: " . $row->artist_name . " - " . $row->album_name;
            $song["mp3"] = "{$musicUrlPre}/{$row->artist_name}/{$row->album_name}/{$row->file_name}";
            array_push($song_array, $song);
        }

        $this->response->type('application/json');
        return json_encode($song_array);
    }

    public function action_download($songs = 0)
    {
        $query = "select
        s.id as song_id, s.file_name as song_name, al.name as album_name, ar.name as artist_name
        from song s
        join album al
        on   s.album_id = al.id
        join artist ar
        on   al.artist_id = ar.id
        where s.id in ([?])
        ";
        $dir = Config::get( "music.dir" );
        $downloadFileName = $dir . "/" . time() . $_SERVER['REMOTE_ADDR'] . '.zip';

        
        $rows = Database::all($query, array( explode(',', $songs) ));
        $zip = new \ZipArchive;
        $zip->open($downloadFileName, \ZipArchive::CREATE);
        foreach ($rows as $row) {
            $fileName = "{$dir}/{$row->artist_name}/{$row->album_name}/{$row->song_name}";
            $zip->addFile($fileName, basename($fileName));
        }
        $zip->close();
        File::download( $downloadFileName, null, null, 0, function($file)
        {
            unlink($file);
        });

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

    public function action_showdynamiclyric($id=0)
    {
        $query = "select lrc_lyric from song where id = ?";
        $lyric = Database::column($query, array($id));
        if (! empty($lyric)) {
            return $lyric;
        } else {
            return "";
        }
    }
}

?>
