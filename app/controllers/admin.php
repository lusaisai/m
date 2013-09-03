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
        					$this->connection->query("insert into song_w (name, artist_name, album_name) values(?,?,?)", array(static::toUtf8($songName), static::toUtf8($artistName), static::toUtf8($albumName)));
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
        return mb_convert_encoding( $value , "utf8", "cp936");
    }


	private static function isImage($name)
	{
		return preg_match('/(jpg|jpeg|png)$/', $name);
	}

	private static function isSong($name)
	{
		return preg_match('/(mp3|m4a)$/', $name);
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
        imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
        imagejpeg( $tmp_img, $newImgName );
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

