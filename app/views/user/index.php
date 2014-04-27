<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/ico" href="/m/assets/img/favicon.ico"/>
        <title><?php include dirname(__FILE__) . '/../poem.php';?></title>
        <?php include dirname(__FILE__) . '/../assets.php';?>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-pills">
                <li><a href="<?php echo URL::to('home'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist'); ?>">Artist</a></li>
                <li><a href="<?php echo URL::to('album'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song'); ?>">Song</a></li>
                <li class="active pull-right">
                    <?php if (Session::get( "isLogin", false )): ?>
                        <a href="<?php echo URL::to('user/logout'); ?>">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo URL::to('user/login'); ?>">Login</a>
                    <?php endif ?>
                </li>
            </ul>
            <div class="row">
                <div class="col-md-3">
                    <?php include dirname(__FILE__).'/../player.html'; ?>
                </div>
                <div class="col-md-8">
                    <div id="side" class="col-md-3">
                        <ul class="nav nav-pills nav-stacked">
                            <li id="playlist" class="active"><a href="javascript:;">My Playlists</a></li>
                            <li id="info"><a href="javascript:;">My Information</a></li>
                            <?php if ( Session::get( "role", "" ) == "admin" ): ?>
                                <li id="admin"><a href="javascript:;">Admin</a></li>
                            <?php endif ?>
                        </ul>
                    </div>
                    <div id="data" class="col-md-8">
                        <?php include 'playlist.php'; ?>
                        <div class="playlistdetail"></div>
                    </div>
                    <script>
                        $('#playlist').click(function(event) {
                            $("#side li").removeClass('active');
                            $(this).addClass('active');
                            $('#data').load('/m/user/showplaylist', function() {
                                $(this).append('<div class="playlistdetail"></div>');
                            });

                        });
                        $('#info').click(function(event) {
                            $("#side li").removeClass('active');
                            $(this).addClass('active');
                            $('#data').load('/m/user/updateinfo');
                        });
                        $('#admin').click(function(event) {
                            $("#side li").removeClass('active');
                            $(this).addClass('active');
                            $('#data').load('/m/user/showadmin');
                        });
                    </script>
                </div>
            </div>
        </div>
    </body>
</html>
