<?php
    Assets::add('jquery', '/js/jquery-1.10.2.min.js');
    Assets::add('jplayer-skin', '/jplayer/skin/pink.flag/jplayer.pink.flag.css');
    Assets::add('jplayer', '/jplayer/js/jquery.jplayer.min.js');
    Assets::add('jplayer-playlist', '/jplayer/js/jplayer.playlist.min.js');
    Assets::add('play', '/js/play.js');
    Assets::add('bootstrap-css', '/bootstrap/css/bootstrap.css');
    Assets::add('bootstrap-js', '/bootstrap/js/bootstrap.min.js');
    Assets::add('main-css', '/css/main.css');
    echo Assets::all();
?>
