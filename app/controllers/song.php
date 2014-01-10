<?php
namespace app\controllers;
use mako\Database;
use mako\View;


class Song extends \mako\Controller {
    public function action_index($pageid = 1) {
        return new View("song.index", $this->fetchData($pageid));
    }

    public function action_load($pageid = 1) {
        return new View("song.data", $this->fetchData($pageid));
    }

    private function fetchData($pageid = 1) {
        $limit = 50;
        $data = array();

        $songIds = $this->searchSongs();
        $count = count($songIds);
        $toalPages = ceil($count / $limit);
        if($pageid > $toalPages) $pageid = 1;
        $offset = ($pageid - 1) * $limit;
        $thisPageIds = array_slice($songIds, $offset, $limit);

        foreach ($thisPageIds as $songId) {
            array_push($data, $this->songInfo($songId));
        }

        return array( 'pageid'=>$pageid, 'count'=>$count, 'limit'=>$limit, 'data'=>$data);
    }

    public function searchSongs(){
            $words = isset($_GET['words']) ? preg_split( "/\s+/", trim($_GET['words']) ) : array();
            $rlike_pinyin = Database::connection()->pdo->quote(implode(",", $words));
            $like_pinyin = Database::connection()->pdo->quote('%'.implode(",", $words).'%');


            $type = isset($_GET['type']) ? trim($_GET['type']) : "songname";

            if( empty($words) ){
                $query = "select id from song order by id desc";
                return $this->querySongIds(array($query));
            } elseif ( $type == "artistname" ) {
                $query = "select
                s.id
                from song s
                join album al
                on   s.album_id = al.id
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
                $query = "select
                    s.id
                    from song s
                    join album al
                    on   s.album_id = al.id
                    ";
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
                $query = "select s.id from song s";
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

            return $this->querySongIds( array( $pinyinRlikeQuery, $pinyinLikeQuery, $andQuery, $orQuery ) );
        }

        private function querySongIds( $queries ) {
            $songIds = array();
            //$log = \mako\Log::instance();

            foreach( $queries as $query ) {
                //$log->write($query);
                $songs = Database::all($query);
                foreach ($songs as $song) {
                    array_push($songIds, $song->id);
                }
            }

            return array_unique($songIds);
        }

        private function songInfo($songId) {
            $query = "select
            s.id, s.name as song_name, ar.name as artist_name, al.name as album_name
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            where s.id = $songId
            ";
            $row = Database::first($query);

            return array(
                'id' => $row->id,
                'song_name'=> $row->song_name,
                'artist_name'=> $row->artist_name,
                'album_name'=>$row->album_name
            );
        }

}

?>
