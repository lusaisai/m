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
            <ul class="nav nav-pills">
                <li class="active"><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
                <li><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song/index'); ?>">Song</a></li>
                <li class="pull-right">
                    <?php if ( Session::get( "isLogin", false ) ) {
                        echo "<a href='" . URL::to('user/index') . "'>" . Session::get("username") . "</a>";
                    } else {
                        echo "<a href='" . URL::to('user/login') . "'>Login</a>";
                    }
                     ?>
                </li>
            </ul>
            <div class="row">
                <div class="span4">
                    <?php include dirname(__FILE__).'/../player.html'; ?>
                </div>
                <div class="span8">
                    <div id="randoms">
                        <?php include 'randoms.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
