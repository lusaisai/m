<?php
    Assets::add('jquery-js', '/js/jquery-1.10.2.min.js');

    Assets::add('jquery-lightbox-js', '/lightbox/js/lightbox-2.6.min.js');
    Assets::add('jquery-lightbox-css', '/lightbox/css/lightbox.css');

    Assets::add('jquery-cookie-js', '/js/jquery.cookie.js');

    Assets::add('jplayer-js', '/jplayer/js/jquery.jplayer.min.js');
    Assets::add('jplayer-playlist-js', '/jplayer/js/jplayer.playlist.js');

    Assets::add('tagcanvas-js', '/js/jquery.tagcanvas.min.js');

    Assets::add('play-js', '/js/play.js');
    // Assets::add('complete-js', '/js/complete.js');

    Assets::add('bootstrap-css', '/bootstrap/css/bootstrap.min.css');
    Assets::add('bootstrap-js', '/bootstrap/js/bootstrap.min.js');
    Assets::add('bootbox-js', '/js/bootbox.min.js');

    Assets::add('main-css', '/css/main.css');

    Assets::add('jplayer-skin-css', '/jplayer/skin/bootstrap/style.css');

    echo Assets::all();
?>
