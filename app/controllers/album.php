<?php

namespace app\controllers;
use mako\Database;
use mako\View;

class Album extends \mako\Controller
{
	public function action_index($pageid = 1)
	{
            return new View("album.index", $this->fetchData($pageid));
	}

        public function action_load($pageid = 1)
	{
            return new View("album.data", $this->fetchData($pageid));
	}

        private function fetchData($pageid = 1) {
            $limit = 5;
            $data = array();

            $albumIds = $this->searchAlbums();
            $count = count($albumIds);
            $toalPages = ceil($count / $limit);
            if($pageid > $toalPages) $pageid = 1;
            $offset = ($pageid - 1) * $limit;
            $thisPageIds = array_slice($albumIds, $offset, $limit);

            foreach ($thisPageIds as $albumId) {
                array_push($data, $this->albumInfo($albumId));
            }

            return array( 'pageid'=>$pageid, 'count'=>$count, 'limit'=>$limit, 'data'=>$data);
        }

        public function searchAlbums(){
            $words = isset($_GET['words']) && trim($_GET['words']) != '' ? preg_split( "/\s+/", trim($_GET['words']) ) : array();
            $type = isset($_GET['type']) ? trim($_GET['type']) : "artistname";
            $rlike_pinyin = Database::connection()->pdo->quote(implode(",", $words));
            $like_pinyin = Database::connection()->pdo->quote('%'.implode(",", $words).'%');

            if( empty($words) ){
                $query = "select id from album order by id desc";
                return $this->queryAlbumIds(array($query));
            } elseif ( $type == "artistname" ) {
                $query = "select
                al.id
                from album al
                join artist ar
                on   al.artist_id = ar.id
                ";
                $pinyinRlikeQuery = $query . " where " . $rlike_pinyin . " rlike ar.name_pinyin";
                $pinyinLikeQuery = $query . " where REPLACE( REPLACE(ar.name_pinyin, '(', ''), ')', '' ) like " . $like_pinyin;
                $andQuery = $query . " where true ";
                $orQuery = $query . " where false ";

                foreach( $words as $word ) {
                    $word = Database::connection()->pdo->quote('%'.trim($word).'%');
                    $andQuery .= " and ar.name like $word ";
                    $orQuery .= " or ar.name like $word ";
                }
                $andQuery .= " order by id desc ";
                $orQuery .= " order by id desc ";
            } elseif ( $type == "albumname" ) {
                $query = "select al.id from album al";
                $pinyinRlikeQuery = $query . " where " . $rlike_pinyin . " rlike al.name_pinyin";
                $pinyinLikeQuery = $query . " where REPLACE( REPLACE(al.name_pinyin, '(', ''), ')', '' ) like " . $like_pinyin;
                $andQuery = $query . " where true ";
                $orQuery = $query . " where false ";

                foreach( $words as $word ) {
                    $word = Database::connection()->pdo->quote('%'.trim($word).'%');
                    $andQuery .= " and al.name like $word ";
                    $orQuery .= " or al.name like $word ";
                }
                $andQuery .= " order by id desc ";
                $orQuery .= " order by id desc ";
            } elseif ( $type == "songname" ) {
                $query = "select
                al.id
                from song s
                join album al
                on   s.album_id = al.id";
                $pinyinRlikeQuery = $query . " where " . $rlike_pinyin . " rlike s.name_pinyin";
                $pinyinLikeQuery = $query . " where REPLACE( REPLACE(s.name_pinyin, '(', ''), ')', '' ) like " . $like_pinyin;
                $andQuery = $query . " where true ";
                $orQuery = $query . " where false ";

                foreach( $words as $word ) {
                    $word = Database::connection()->pdo->quote('%'.trim($word).'%');
                    $andQuery .= " and s.name like $word ";
                    $orQuery .= " or s.name like $word ";
                }
                $andQuery .= " group by id order by id desc ";
                $orQuery .= " group by id order by id desc ";
            }

            return $this->queryAlbumIds( array( $pinyinRlikeQuery, $pinyinLikeQuery, $andQuery, $orQuery ) );
        }

        private function queryAlbumIds( $queries ) {
            $albumIds = array();

            foreach( $queries as $query ) {
                $albums = Database::all($query);
                foreach ($albums as $album) {
                    array_push($albumIds, $album->id);
                }
            }

            return array_unique($albumIds);
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

            $songs = array();
            $query = "select 
            s.id, 
            s.name,
            case when hot.id is not null then 1 else 0 end as is_hot
            from song s
            left join topsongs hot
            on  s.id = hot.id
            where s.album_id = $albumId";
            $songrows = Database::all($query);
            foreach( $songrows as $songrow ) {
                array_push($songs, array( 'id'=>$songrow->id, 'name'=>$songrow->name, 'is_hot' => $songrow->is_hot ));
            }

            return array( 'id' => $albumId, 'artist_name'=>$artist_name, 'album_name'=>$album_name, 'image'=>$image, 'songs'=>$songs );
        }


}
