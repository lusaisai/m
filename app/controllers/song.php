<?php
namespace app\controllers;
use mako\Database;
use mako\View;
use mako\Config;


class Song extends \mako\Controller {
    public function action_index($pageid = 1) {
        $_GET['pageid'] = $pageid;
        $isCache = Config::get('music.use_cache');
        if ($isCache) {
            $result = Hash::find_cache($this->request->controller(), $this->request->action(), $_GET );
            if ($result) return $result;
        }

        $view = new View("song.index", $this->fetchData($pageid));
        if ($isCache) Hash::store_cache($this->request->controller(), $this->request->action(), $_GET, $view);
        return $view;
    }

    public function action_load($pageid = 1) {
        $_GET['pageid'] = $pageid;
        $isCache = Config::get('music.use_cache');
        if ($isCache) {
            $result = Hash::find_cache($this->request->controller(), $this->request->action(), $_GET );
            if ($result) return $result;
        }
        $view = new View("song.data", $this->fetchData($pageid));
        if ($isCache) Hash::store_cache($this->request->controller(), $this->request->action(), $_GET, $view);
        return $view;
    }

    private function fetchData($pageid = 1) {
        // $log = \mako\Log::instance();
        // $log->write("search songs starts");
        // $log->write(microtime());

        $limit = 50;

        $songIds = $this->searchSongs();

        // $log->write("search songs ends");
        // $log->write(microtime());

        $count = count($songIds);
        $toalPages = ceil($count / $limit);
        if($pageid > $toalPages) $pageid = 1;
        $offset = ($pageid - 1) * $limit;
        $thisPageIds = array_slice($songIds, $offset, $limit);

        // $log->write("get songs info starts");
        // $log->write(microtime());

        $data = $this->songsInfo($thisPageIds);

        // $log->write("get songs info ends");
        // $log->write(microtime());

        return array( 'pageid'=>$pageid, 'count'=>$count, 'limit'=>$limit, 'data'=>$data);
    }

    public function searchSongs(){
            $words = isset($_GET['words']) && trim($_GET['words']) != '' ? preg_split( "/\s+/", trim($_GET['words']) ) : array();
            $rlike_pinyin = Database::connection()->pdo->quote(implode(",", $words));
            $like_pinyin = Database::connection()->pdo->quote('%'.implode(",", $words).'%');


            $type = isset($_GET['type']) ? trim($_GET['type']) : "songname";

            if( empty($words) ){
                $query = "select GROUP_CONCAT(id order by id desc) ids from song";
                return $this->querySongIds(array($query));
            } elseif ( $type == "artistname" ) {
                $query = "select
                GROUP_CONCAT(s.id order by s.id desc) ids
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
            } elseif ( $type == "albumname" ) {
                $query = "select
                    GROUP_CONCAT(s.id order by s.id desc) ids
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
            } elseif ( $type == "songname" ) {
                $query = "select GROUP_CONCAT(s.id order by s.id desc) ids from song s";
                $pinyinRlikeQuery = $query . " where " . $rlike_pinyin . " rlike s.name_pinyin";
                $pinyinLikeQuery = $query . " where REPLACE( REPLACE(s.name_pinyin, '(', ''), ')', '' ) like " . $like_pinyin;
                $andQuery = $query . " where true ";
                $orQuery = $query . " where false ";

                foreach( $words as $word ) {
                    $word = Database::connection()->pdo->quote('%'.trim($word).'%');
                    $andQuery .= " and s.name like $word ";
                    $orQuery .= " or s.name like $word ";
                }
            }

            return $this->querySongIds( array( $pinyinRlikeQuery, $pinyinLikeQuery, $andQuery, $orQuery ) );
        }

        private function querySongIds( $queries ) {
            $ids = "";
            // $log = \mako\Log::instance();
            
            foreach( $queries as $query ) {
                // $log->write($query);
                // $log->write("query starts");
                // $log->write(microtime());
                $ids .= Database::column($query);
                // $log->write("query ends");
                // $log->write(microtime());
            }

            // $log->write("array unique starts");
            // $log->write(microtime());
            $data = array_unique( explode(",", $ids) );
            // $log->write("array unique ends");
            // $log->write(microtime());

            return $data;
        }

        private function songsInfo($songIds) {
            $query = "select
            s.id, s.name as song_name, ar.name as artist_name, al.name as album_name,
            case when hot.id is not null then 1 else 0 end as is_hot
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            left join topsongs hot
            on  s.id = hot.id
            where s.id in ([?])
            ";
            return Database::all($query, array($songIds));
        }

        private function singleSongInfo($songId) {
            $query = "select
            s.id, s.name as song_name, ar.name as artist_name, al.name as album_name,
            case when hot.id is not null then 1 else 0 end as is_hot
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id
            left join topsongs hot
            on  s.id = hot.id
            where s.id = $songId
            ";
            $row = Database::first($query);

            return array(
                'id' => $row->id,
                'song_name'=> $row->song_name,
                'artist_name'=> $row->artist_name,
                'album_name'=>$row->album_name,
                'is_hot' => $row->is_hot
            );
        }

}

?>
