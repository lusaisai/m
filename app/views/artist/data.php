<?php
foreach( $data as $artist ) {
    $artistID = $artist['artist_id'];
    $artistName = $artist['artist_name'];
    $imageName = $artist['image_name'];
    $albums = $artist['albums'];

    echo "<div id='{$artistID}' class='album'>";
    echo "<blockquote><h3>$artistName</h3></blockquote>";
    echo "<div><img src='/music/{$artistName}/{$imageName}' class='img-rounded album-image'></img></div>";
    echo "<div class='accordion slide' id='accordion$artistID'>";

    foreach ($albums as $album) {
        $albumID = $album['album_id'];
        $albumName = $album['album_name'];
        $songs = $album['songs'];
        echo '<div class="accordion-group">';
        echo '<div class="accordion-heading">';
        echo "<a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion$artistID' href='#collapse$albumID'>$albumName</a>";
        echo "</div>";
        echo "<div id='collapse$albumID' class='accordion-body collapse'>";
        echo "<div class='accordion-inner songs'>";
        echo "<table class='table table-bordered table-hover table-condensed'>";
        foreach( $songs as $song ) {
            echo "<tr>";
            echo "<td>";
            echo '<label class="checkbox">';
            echo "<input type='checkbox' checked='checked' songid='{$song['song_id']}'>";
            echo $song['song_name'];
            echo "</label>";
            echo "</td>";
            echo "<td style='text-align:center'><button class='btn btn-mini song-play' type='button' songid='{$song['song_id']}'><i class='icon-headphones'></i></button></td>";
            echo "<td style='text-align:center'><button class='btn btn-mini song-add' type='button' songid='{$song['song_id']}'><i class='icon-plus'></i></button></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo '<div class="btn-group">';
        echo '<button class="btn reverse-check" type="button">Reverse Check</button>';
        echo '<button class="btn check-all" type="button">Check All</button>';
        echo '<button class="btn uncheck-all" type="button">Uncheck All</button>';
        echo "</div>"; //toggle check div
        echo "</div>"; //accordion inner
        echo "</div>"; //accordion body
        echo "</div>"; //accordion group
    }
    echo "</div>"; //accordion

    echo '<div class="btn-group">';
    echo '<button class="btn btn-primary song-list"><i class="icon-list icon-white"></i></button>';
    echo "<button artist_id='{$artistID}' class='btn btn-primary artist-play' type='button'><i class='icon-music icon-white'></i> Play</button>";
    echo "</div>";

    echo "</div>";
}

include dirname(__FILE__) . '/../pagination.php';

?>
