<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>User</title>
        <?php include dirname(__FILE__) . '/../assets.php';?>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-pills">
                <li><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
                <li><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song/index'); ?>">Song</a></li>
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
                    <div class="alert alert-success">You are logged in!</div>
                </div>
                </div>
            </div>
        </div>
    </body>
</html>