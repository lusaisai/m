<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>My Music Station</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include dirname(__FILE__) . '/../assets.php';?>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
                <li class="active"><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song/index'); ?>">Song</a></li>
            </ul>
            <div class="row">
                <div class="span4">
                    <?php include dirname(__FILE__).'/../player.php'; ?>
                </div>
                <div class="span8">
                    <div id="searching">
                        <form class="form-search" action="<?php echo URL::to("album/index/$pageid"); ?>" method="get">
                            <input name="words" type="text" value="<?php if(isset($_GET['words'])) echo $_GET['words'];?>" class="input-xlarge" placeholder="品冠">
                        <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
                        <select name="type" style="width:120px">
                            <option value="artistname" <?php if( isset($_GET['type']) && $_GET['type'] == "artistname" ) echo 'selected="selected"'; ?> >Artist Name</option>
                            <option value="albumname" <?php if( isset($_GET['type']) && $_GET['type'] == "albumname" ) echo 'selected="selected"'; ?>>Album Name</option>
                            <option value="songname" <?php if( isset($_GET['type']) && $_GET['type'] == "songname" ) echo 'selected="selected"'; ?>>Song Name</option>
                        </select>
                    </form>
                    </div>
                    <div id="info"></div>
                    <?php 
                    foreach( $data as $album ) {
                        $albumId = $album['id'];
                        $albumName = $album['album_name'];
                        $artistName = $album['artist_name'];
                        $image = $album['image'];
                        $songs = $album['songs'];
                        
                        echo "<div id='{$albumId}' class='album'>";
                        echo "<blockquote><p>{$albumName}</p><small>{$artistName}</small></blockquote>";
                        echo "<div><img src='/music/{$artistName}/{$albumName}/{$image}' class='img-rounded album-image'></img></div>";
                        echo "<div class='songs'>";
                        echo "<table class='table table-bordered table-hover'>";
                        foreach( $songs as $song ) {
                            echo "<tr>";
                            echo "<td>";
                            echo '<label class="checkbox">';
                            echo "<input type='checkbox' checked='checked' songid='{$song['id']}'>";
                            echo $song['name'];
                            echo "</label>";
                            echo "</td>";
                            echo "<td style='text-align:center'><button class='btn btn-mini song-play' type='button' songid='{$song['id']}' songname='{$song['name']}'><i class='icon-headphones'></i></button></td>";
                            echo "<td style='text-align:center'><button class='btn btn-mini song-add' type='button' songid='{$song['id']}'><i class='icon-plus'></i></button></td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                        echo '<div class="btn-group">';
                        echo '<button class="btn reverse-check" type="button">Reverse Check</button>';
                        echo '<button class="btn check-all" type="button">Check All</button>';
                        echo '<button class="btn uncheck-all" type="button">Uncheck All</button>';
                        echo "</div>";
                        echo "</div>";
                        echo '<div class="btn-group">';
                        echo '<button class="btn btn-primary song-list"><i class="icon-list icon-white"></i></button>';
                        echo "<button album_id='{$albumId}' class='btn btn-primary album-play' type='button'><i class='icon-music icon-white'></i> Play</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                    
                    // the pagination
                    $prevPageId = $pageid - 1;
                    $nextPageId = $pageid + 1;
                    $toalPages = ceil($count / $limit);
                    $startid = $pageid - 5 > 1 ? $pageid - 5 : 1;
                    $endid = $pageid + 4 < $toalPages ? $pageid + 4 : $toalPages;
                    
                    echo '<div class="pagination"><ul>';
                    if ( $startid > 1 ) {
                        echo "<li><a href='" . URL::to("album/index/1", $_GET ) . "'>First</a></li>";
                        echo "<li><a>...</a></li>";
                    }
                    for( $i = $startid; $i <= $endid; $i++ ) {
                        if ( $i == $pageid ) {
                            echo "<li class='active'><a href='" . URL::to("album/index/$i", $_GET ) . "'>$i</a></li>";
                        } else {
                            echo "<li><a href='" . URL::to("album/index/$i", $_GET ) . "'>$i</a></li>";
                        }
                    }
                    if ( $endid < $toalPages ) {
                        echo "<li><a>...</a></li>";
                        echo "<li><a href='" . URL::to("album/index/$toalPages", $_GET ) . "'>Last</a></li>";
                    }
                    echo '</ul></div>';
                    ?>
                </div>
                    
                </div>
            </div>
        </div>
    </body>
</html>
