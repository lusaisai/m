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
    echo "<div class='slide songs'>";
    echo "<table class='table table-bordered table-hover table-condensed'>";
    foreach( $songs as $song ) {
        echo "<tr>";
        echo "<td>";
        echo '<label class="checkbox">';
        echo "<input type='checkbox' checked='checked' songid='{$song['id']}'>";
        echo $song['name'];
        echo "</label>";
        echo "</td>";
        echo "<td style='text-align:center'><button class='btn btn-mini song-play' type='button' songid='{$song['id']}'><i class='icon-headphones'></i></button></td>";
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

include dirname(__FILE__) . '/../pagination.php';

?>
