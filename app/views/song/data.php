<?php 
    
echo "<div class='songs'>";
echo "<table class='table table-bordered table-hover table-condensed'>";
    
foreach( $data as $song ) {
    $songId = $song['id'];
    $songName = $song['song_name'];
    $albumName = $song['album_name'];
    $artistName = $song['artist_name'];

    echo "<tr>";
    echo "<td>";
    echo '<label class="checkbox">';
    echo "<input type='checkbox' checked='checked' songid='$songId'>";
    echo $songName;
    echo "</label>";
    echo "</td>";
    echo "<td>$artistName</td>";
    echo "<td>$albumName</td>";
    echo "<td style='text-align:center'><button class='btn btn-mini song-play' type='button' songid='$songId' songname='$songName'><i class='icon-headphones'></i></button></td>";
    echo "<td style='text-align:center'><button class='btn btn-mini song-add' type='button' songid='$songId'><i class='icon-plus'></i></button></td>";
    echo "</tr>";
}

echo "</table>";
echo '<div class="btn-group">';
echo '<button class="btn reverse-check" type="button">Reverse Check</button>';
echo '<button class="btn check-all" type="button">Check All</button>';
echo '<button class="btn uncheck-all" type="button">Uncheck All</button>';
echo "</div>";
echo '<div class="btn-group">';
echo "<button class='btn btn-primary album-play' type='button'><i class='icon-music icon-white'></i> Play</button>";
echo "</div>";
echo "</div>";

include dirname(__FILE__) . '/../pagination.php';
?>
