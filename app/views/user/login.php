<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Login</title>
        <?php include dirname(__FILE__) . '/../assets.php';?>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-pills">
                <li><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
                <li><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
                <li><a href="<?php echo URL::to('song/index'); ?>">Song</a></li>
                <li class="active pull-right"><a href="<?php echo URL::to('user/login'); ?>">Login</a></li>
            </ul>
            <div class="row">
                <div class="span4">
                    <?php include dirname(__FILE__).'/../player.html'; ?>
                </div>
                <div class="span8">
                    <?php if ($errors != ""): ?>
                        <div class="alert alert-error"><?php echo $errors; ?></div>
                    <?php endif ?>
                    <div id="data" pagetype="login">
                        <?php include 'loginform.php';?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </body>
</html>
