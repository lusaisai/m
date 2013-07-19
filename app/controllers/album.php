<?php

namespace app\controllers;
use mako\Database;
use mako\View;

class Album extends \mako\Controller
{
	public function action_index($pageid = 1)
	{
            $count = Database::column("select count(*) from album");
            $limit = 5;
            $offset = ($pageid - 1) * 5;
            $data = array();
            
            $query = "select id from album order by id desc limit $offset, $limit";
            $albumIds = Database::all($query);
            
            foreach ($albumIds as $albumId) {
                array_push($data, $this->albumInfo($albumId->id));
            }

            return new View('album.index', array( 'pageid'=>$pageid, 'count'=>$count, 'limit'=>$limit, 'data'=>$data));
	}
        
        private function albumInfo($albumId) {
            $query = "select
            al.name as album_name, ar.name as artist_name
            from album al
            join artist ar
            on   al.artist_id = ar.id
            where al.id = $albumId
            ";
            $row = Database::first($query);
            $artist_name = $row->artist_name;
            $album_name = $row->album_name;
            
            $query = "select
            im.name as image_name
            from album al
            join image im
            on   al.id = im.album_id
            where al.id = $albumId
            order by rand()
            limit 1
            ";
            $image = Database::column($query);
            return array( 'id' => $albumId, 'artist_name'=>$artist_name, 'album_name'=>$album_name, 'image'=>$image );
        }
        
        
}
