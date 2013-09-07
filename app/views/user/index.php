<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/ico" href="/m/assets/img/favicon.ico"/>
        <title>User</title>
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
                <div class="span4">
                    <?php include dirname(__FILE__).'/../player.html'; ?>
                </div>
                <div class="span8">
                    <div id="side" class="span2">
                        <ul class="nav nav-tabs nav-stacked">
                            <li class="active"><a href="javascript:;">My Playlists</a></li>
                            <li><a href="javascript:;">My Information</a></li>
                            <?php if ( Session::get( "role", "" ) == "admin" ): ?>
                                <li><a href="javascript:;">Admin</a></li>
                            <?php endif ?>
                        </ul>
                    </div>
                    <div id="content" class="span6">
                        <?php include 'playlist.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
