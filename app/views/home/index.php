<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/ico" href="/m/assets/img/favicon.ico"/>
        <title>My Music Station</title>
        <?php include dirname(__FILE__) . '/../assets.php';?>
    </head>
    <body>
        <div class="container">
            <?php include dirname(__FILE__).'/../info.php'; ?>
            <ul class="nav nav-pills">
                <li class="active"><a href="<?php echo URL::to('home'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist'); ?>">Artist</a></li>
                <li><a href="<?php echo URL::to('album'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song'); ?>">Song</a></li>
                <li class="pull-right">
                    <?php if ( Session::get( "isLogin", false ) ) {
                        echo "<a href='" . URL::to('user') . "'>" . Session::get("username") . "</a>";
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
                    <div id="topsongs" class="tops">
                        <?php include 'topsongs.php'; ?>
                    </div>
                    <div id="topartists" class="tops">
                        <?php include 'topartists.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
