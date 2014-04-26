<?php
namespace app\controllers;

use \mako\Database;
use \mako\View;
use \mako\Session;
use \mako\Config;


class Admin extends \mako\Controller
{

	public function before()
	{
		$this->connection = Database::connection();
	}

	public function action_update()
	{
		$data = array( 'error' => '', 'success' => '', 'newsongs' => '' );

        if ( Session::get( "role", "" ) != "admin" ) {
            $data['error'] = "You are not authorized to perform this action!";
            return new View( "admin.data", $data );
        }

        $this->createTables();
        $this->stageTableClean();
        $this->syncAutoIncre();

        $this->connection->pdo->beginTransaction();
		$this->populateStaging();
        $this->upsert();
        $this->connection->pdo->commit();

        $this->tableRename();

        $data['success'] = 'Update Completed';
        $data['newsongs'] = $this->newSong();
        if (Config::get('music.use_cache')) Hash::clear_cache_all();
        return new View( "admin.data", $data );
	}

    private function newSong()
    {
        $query = "select
                  s.name as song_name, al.name as album_name, ar.name as artist_name
                  from song s
                  join album al
                  on   s.album_id = al.id
                  join artist ar
                  on   al.artist_id = ar.id
                  where s.id not in (select id from song_old)";
        return $this->connection->all( $query );
    }

    public function action_pinyin()
    {
        set_time_limit(1800);
        $data = array( 'error' => '', 'success' => '', 'newsongs' => '' );

        if ( Session::get( "role", "" ) != "admin" ) {
            $data['error'] = "You are not authorized to perform this action!";
            return new View( "admin.data", $data );
        }

        $this->connection->pdo->beginTransaction();

        // update the artist
        $query = "select id, name from artist where name_pinyin is null";
        $update_query = "update artist set name_pinyin = ? where id = ?";
        $data['success'] .= static::pinyinUpd($query, $update_query);

        // update the album
        $query = "select id, name from album where name_pinyin is null";
        $update_query = "update album set name_pinyin = ? where id = ?";
        $data['success'] .= static::pinyinUpd($query, $update_query);

        // update the song
        $query = "select id, name from song where name_pinyin is null";
        $update_query = "update song set name_pinyin = ? where id = ?";
        $data['success'] .= static::pinyinUpd($query, $update_query);

        $this->connection->pdo->commit();

        return new View( "admin.data", $data );
    }

    private static function pinyinUpd($query, $update_query)
    {
        $data = '';
        $rows = Database::all($query);

        foreach ($rows as $row) {
            $id = $row->id;
            $name = $row->name;
            $name_pinyin = static::toPinyin($name);
            // $name_pinyin = Database::connection()->pdo->quote($name_pinyin);
            Database::query( $update_query, array( $name_pinyin, $id ) );
            $data .= $name . ' - ' . $name_pinyin . '<br />';
        }

        return $data;
    }

    private static function toPinyin($word='')
    {
        $pinyin = "";
        foreach (static::mbSplit($word) as $char) {
            $quoted_char = Database::connection()->pdo->quote($char);
            $query = "SELECT
                  concat_ws( '|', pinyin1,pinyin2,pinyin3,pinyin4,pinyin5,pinyin6 ) as pinyin
                  FROM pinyin_map
                  where chinese_word = {$quoted_char}";
            $char_pinyin = Database::column($query);
            if ($char_pinyin == '') {
                $char_pinyin = $char;
            }
            $pinyin .= '(' . preg_replace('/\|+$/', '', $char_pinyin) . ')';
        }

        
        return $pinyin;
    }

    private static function mbSplit($word)
    {
        return preg_split('/(?<!^)(?!$)/u', $word );
    }

    public function action_lyric()
    {
        set_time_limit(1800);
        $data = array( 'error' => '', 'success' => '', 'newsongs' => '' );

        if ( Session::get( "role", "" ) != "admin" ) {
            $data['error'] = "You are not authorized to perform this action!";
            return new View( "admin.data", $data );
        }

        $jarFile = MAKO_APPLICATION_PATH . "/LyricSearch/target/LyricSearch-1.0-SNAPSHOT-jar-with-dependencies.jar";
        $command = "LANG=en_US.UTF-8; java -jar $jarFile";
        $data['success'] = `$command`;
        return new View( "admin.data", $data );
    }

	private function stageTableClean()
	{
		$tables = array( "artist_new", "artist_w", "album_new", "album_w", "song_new", "song_w", "image_new", "image_w" );
		foreach ($tables as $table) {
			$this->connection->query( "delete from $table" );
		}
	}

	private function syncAutoIncre()
	{
		$tables = array("artist", "album", "song", "image");
		foreach ($tables as $table) {
			$count = $this->connection->column( "select coalesce( max(id) + 1, 1 ) as cnt from $table" );
			$this->connection->query( "ALTER TABLE {$table}_new AUTO_INCREMENT = $count" );
		}
	}

	private function populateStaging()
	{
		$musicDir = Config::get( "music.dir" );
		$musicHandle = opendir($musicDir);
		chdir($musicDir);
		while (false !== ($artistName = readdir($musicHandle))) {
			// insert artists
        	if ( $artistName == "." || $artistName == ".." || ! is_dir($artistName) ) continue;
        	$this->connection->query("insert into artist_w (name) values(?)", array( static::toUtf8( $artistName ) ));

        	// insert albums and artist images
        	$artistHandle = opendir($artistName);
        	chdir($artistName);
        	while (false !== ($albumName = readdir($artistHandle))) {
        		if ( $albumName == "." || $albumName == ".." ) continue;
        		if (is_dir($albumName)) {
        			$this->connection->query("insert into album_w (name, artist_name) values(?,?)", array( static::toUtf8($albumName), static::toUtf8($artistName) ));
        			// insert songs and album images
        			$albumHandle = opendir($albumName);
        			chdir($albumName);
        			while (false !== ($songName = readdir($albumHandle))) {
        				if ( $songName == "." || $songName == ".." ) continue;
        				if ( static::isSong($songName) ) {
        					$this->connection->query("insert into song_w (name, file_name, artist_name, album_name) values(?,?,?,?)", array( static::toUtf8( static::songClean($songName) ), static::toUtf8($songName), static::toUtf8($artistName), static::toUtf8($albumName)));
        				} elseif (static::isImage($songName)) {
        					$albumImage = $songName;
                            $this->connection->query("insert into image_w (name, artist_name, album_name) values(?,?,?)", array(static::toUtf8($albumImage), static::toUtf8($artistName), static::toUtf8($albumName) ));
                            static::createThumbs($albumImage);
        				}
        			}
                    chdir("..");

        		} elseif( static::isImage($albumName) ) {
        			$artistImage = $albumName;
        			$this->connection->query("insert into image_w (name, artist_name) values(?,?)", array( static::toUtf8($artistImage), static::toUtf8($artistName) ));
                    static::createThumbs($artistImage);
        		}
        	}
            chdir("..");
    	}

	}

    private function tableRename()
    {
        $tables = array("image", "song", "album", "artist");
        foreach ($tables as $table) {
            $this->connection->query( "rename table {$table} to ${table}_old" );
            $this->connection->query( "rename table {$table}_new to ${table}" );
        }
    }

    private static function toUtf8($value)
    {
        return $value;
        // return mb_convert_encoding( $value , "utf8", "cp936");
    }

    private static function songClean($value)
    {
        $value = trim( preg_replace('/\.[^.]*$/', '', $value) ); // remove file extension
        $value = trim( preg_replace('/^.*-/', '', $value) );
        $value = trim( preg_replace('/[0-9]+[. ]/', '', $value) );
        $value = trim( preg_replace('/\[.*\]/', '', $value) );
        $value = trim( preg_replace('/【.*】/', '', $value) );
        $value = trim( preg_replace('/\(.*\)/', '', $value) );
        $value = trim( preg_replace('/（.*）/', '', $value) );
        $value = trim( preg_replace('/\.[^.]*$/', '', $value) );

        return $value;
    }


	private static function isImage($name)
	{
		return $name != "AlbumArtSmall.jpg" && $name != "Folder.jpg" && preg_match('/(jpg|jpeg|png)$/', $name); // tmp solution to skip winodws thumbnail
	}

	private static function isSong($name)
	{
		return preg_match('/(mp3|m4a)$/i', $name);
	}

    private static function createThumbs($name, $overwrite = false)
    {
        $newImgName = $name . ".tm.gif";
        if( file_exists($newImgName) && ! $overwrite ) return;

        $img = imagecreatefromjpeg($name);
        $width = imagesx( $img );
        $height = imagesy( $img );

        $size = 120;
        if ($width > $height) {
            $new_width = $size;
            $new_height = floor( $height * ( $size / $width ) );
        } else {
            $new_height = $size;
            $new_width = floor( $width * ( $size / $height ) );
        }
        $tmp_img = imagecreatetruecolor( $new_width, $new_height );
        imagecopyresampled( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        imagejpeg( $tmp_img, $newImgName, 100 );
    }

    private function createTables()
    {
        $sql = MAKO_APPLICATION_PATH . "/migrations/song_tables.sql";
        $queries = file_get_contents($sql);
        foreach ( explode(";", $queries) as $query) {
            if(trim($query) == "") continue;
            $this->connection->query($query);
        }
    }

    private function upsert()
    {
        $sql = MAKO_APPLICATION_PATH . "/migrations/upsert.sql";
        $queries = file_get_contents($sql);
        foreach ( explode(";", $queries) as $query) {
            if(trim($query) == "") continue;
            $this->connection->query($query);
        }
    }

}

 ?>
