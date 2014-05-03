<?php

namespace app\controllers;
use mako\Database;
use mako\View;
use mako\Config;

class Artist extends \mako\Controller
{
	public function before()
    {
        $this->cache = new DefaultCache();
    }

    public function action_index($pageid = 1)
	{
        $isCache = Config::get('music.use_cache');
        if ($isCache) {
            $result = $this->cache->find_cache($this->request->controller(), $this->request->action(), array_merge( array('pageid'=>$pageid), $_GET ) );
            if ($result) return $result;
        }

        $view = new View("artist.index", $this->fetchData($pageid));
        if ($isCache) $this->cache->store_cache($this->request->controller(), $this->request->action(), array_merge( array('pageid'=>$pageid), $_GET ), $view);
        return $view;
	}

    public function action_load($pageid = 1)
	{
        $isCache = Config::get('music.use_cache');
        if ($isCache) {
            $result = $this->cache->find_cache($this->request->controller(), $this->request->action(), array_merge( array('pageid'=>$pageid), $_GET ) );
            if ($result) return $result;
        }

        $view = new View("artist.data", $this->fetchData($pageid));
        if ($isCache) $this->cache->store_cache($this->request->controller(), $this->request->action(), array_merge( array('pageid'=>$pageid), $_GET ), $view);
        return $view;
	}

    public function action_id($id)
    {
        $isCache = Config::get('music.use_cache');
        if ($isCache) {
            $result = $this->cache->find_cache($this->request->controller(), $this->request->action(), array_merge( array('pageid'=>$pageid), $_GET ) );
            if ($result) return $result;
        }

        $data = array( 'pageid'=>1, 'count'=>1, 'limit'=>1, 'data'=>array($this->artistInfo($id)) );
        $view = new View("artist.index", $data);
        if ($isCache) $this->cache->store_cache($this->request->controller(), $this->request->action(), array_merge( array('pageid'=>$pageid), $_GET ), $view);
        return $view;
    }

    private function fetchData($pageid = 1) {
        $limit = 5;
        $data = array();

        $artistIDs = $this->searchArtists();
        $count = count($artistIDs);
        $toalPages = ceil($count / $limit);
        if($pageid > $toalPages) $pageid = 1;
        $offset = ($pageid - 1) * $limit;
        $thisPageIds = array_slice($artistIDs, $offset, $limit);

        foreach ($thisPageIds as $artistID) {
            array_push($data, $this->artistInfo($artistID));
        }

        return array( 'pageid'=>$pageid, 'count'=>$count, 'limit'=>$limit, 'data'=>$data);
    }

    public function searchArtists(){
        $words = isset($_GET['words']) && trim($_GET['words']) != '' ? preg_split( "/\s+/", trim($_GET['words']) ) : array();
        $type = isset($_GET['type']) ? trim($_GET['type']) : "artistname";
        $rlike_pinyin = Database::connection()->pdo->quote(implode(",", $words));
        $like_pinyin = Database::connection()->pdo->quote('%'.implode(",", $words).'%');

        if( empty($words) ){
            $query = "select id from artist order by id desc";
            return $this->queryArtistIDs(array($query));
        } elseif ( $type == "artistname" ) {
            $query = "select id from artist ar";
            $pinyinRlikeQuery = $query . " where " . $rlike_pinyin . " rlike ar.name_pinyin";
            $pinyinLikeQuery = $query . " where REPLACE( REPLACE(ar.name_pinyin, '(', ''), ')', '' ) like " . $like_pinyin;
            $andQuery = $query . " where true ";
            $orQuery = $query . " where false ";

            foreach( $words as $word ) {
                $word = Database::connection()->pdo->quote('%'.trim($word).'%');
                $andQuery .= " and name like $word ";
                $orQuery .= " or name like $word ";
            }
            $andQuery .= " order by id desc ";
            $orQuery .= " order by id desc ";
        } elseif ( $type == "albumname" ) {
            $query = "select
            ar.id
            from album al
            join artist ar
            on   al.artist_id = ar.id
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
            $query = "select
            ar.id
            from song s
            join album al
            on   s.album_id = al.id
            join artist ar
            on   al.artist_id = ar.id";
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

        return $this->queryArtistIDs( array( $pinyinRlikeQuery, $pinyinLikeQuery, $andQuery, $orQuery ) );
    }

    private function queryArtistIDs( $queries ) {
        $artistIDs = array();

        foreach( $queries as $query ) {
            $log = \mako\Log::instance();
            $log->write($query);
            $artists = Database::all($query);
            foreach ($artists as $artist) {
                array_push($artistIDs, $artist->id);
            }
        }

        return array_unique($artistIDs);
    }

    private function artistInfo($artistID) {
        $query = "select
        ar.id as artist_id, ar.name as artist_name, i.name as image_name
        from image i
        join artist ar
        on   i.artist_id = ar.id
        and  i.album_id is null
        where ar.id = $artistID
        order by rand()
        ";
        $artistRow = Database::first($query);

        $query = "select id as album_id, name as album_name from album where artist_id = $artistID";
        $albumRows = Database::all($query);

        if (! $albumRows) {
            $this->response->redirect('artist/index', 404);
        }

        $albums = array();

        foreach ($albumRows as $albumRow) {
            $query = "select 
            s.id as song_id, 
            s.name as song_name, 
            case when hot.id is not null then 1 else 0 end as is_hot
            from song s
            left join topsongs hot
            on  s.id = hot.id
            where s.album_id = {$albumRow->album_id}";
            $songRows = Database::all($query);
            $songs = array();
            foreach( $songRows as $songRow ) {
                array_push($songs, array( 'song_id'=>$songRow->song_id, 'song_name'=>$songRow->song_name, 'is_hot' => $songRow->is_hot  ));
            }
            array_push($albums, array( 'album_id'=>$albumRow->album_id, 'album_name'=>$albumRow->album_name, 'songs'=>$songs ));
        }

        return array( 'artist_id' => $artistID, 'artist_name'=>$artistRow->artist_name, 'image_name'=>$artistRow->image_name, 'albums'=>$albums );
    }


}
