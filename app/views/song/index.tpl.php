{% extends:app %}

{% block:nav %}
    <li><a href="<?php echo URL::to('home'); ?>">Home</a></li>
    <li><a href="<?php echo URL::to('artist'); ?>">Artist</a></li>
    <li><a href="<?php echo URL::to('album'); ?>">Album</a></li>
    <li class="active"><a href="<?php echo URL::to('song'); ?>">Song</a></li>
    <li class="pull-right">
        <?php if ( Session::get( "isLogin", false ) ) {
            echo "<a href='" . URL::to('user') . "'>" . Session::get("username") . "</a>";
        } else {
            echo "<a href='" . URL::to('user/login') . "'>Login</a>";
        }
         ?>
    </li>
{% endblock %}

{% block:main %}
    <?php $dir = MAKO_APPLICATION_PATH . "/views/song/" ?>
    <div id="searching">
        <form class="form-inline form-search" method="get">
            <input name="words" data-provide="typeahead" style="width:350px" type="text" class="typeahead form-control" placeholder="后来的我们">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
            <select class="type form-control" name="type" style="width:138px">
                <option value="artistname">Artist Name</option>
                <option value="albumname">Album Name</option>
                <option value="songname" selected="selected">Song Name</option>
            </select>
        </form>
    </div>
    <div id="info"></div>
    <div id="data" pagetype="song">
        <?php include $dir . 'data.php';?>
    </div>
{% endblock %}

