<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>My Music Station</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        Assets::add('jquery', '/js/jquery-1.10.2.min.js');
        Assets::add('jplayer-skin', '/jplayer/skin/pink.flag/jplayer.pink.flag.css');
        Assets::add('jplayer', '/jplayer/js/jquery.jplayer.min.js');
        Assets::add('jplayer-playlist', '/jplayer/js/jplayer.playlist.min.js');
        Assets::add('bootstrap-css', '/bootstrap/css/bootstrap.min.css');
        
        Assets::add('play-js', '/js/play.js');
        Assets::add('main-css', '/css/main.css');
        echo Assets::all();
        ?>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
                <li><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song/index'); ?>">Song</a></li>
            </ul>
            <div class="row">
                <div class="span4">
                    <?php include dirname(__FILE__).'/../player.php'; ?>
                </div>
                <div class="span8">
                    <button id="rplay" class="btn btn-primary" type="button">Play!</button>
                </div>
            </div>
        </div>
    </body>
</html>
