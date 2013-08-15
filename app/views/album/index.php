<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>My Albums</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include dirname(__FILE__) . '/../assets.php';?>
    </head>
    <body>
        <div class="container">
            <ul class="nav nav-pills">
                <li><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
                <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
                <li class="active"><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
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
                    <div id="searching">
                        <form class="form-search" method="get">
                            <input name="words" type="text" class="input-xlarge" placeholder="品冠">
                            <button type="submit" class="btn"><i class="icon-search"></i> Search</button>
                            <select name="type" style="width:120px">
                                <option value="artistname">Artist Name</option>
                                <option value="albumname" selected="selected">Album Name</option>
                                <option value="songname">Song Name</option>
                            </select>
                        </form>
                    </div>
                    <div id="info"></div>
                    <div id="data" pagetype="album">
                        <?php include 'data.php';?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </body>
</html>
