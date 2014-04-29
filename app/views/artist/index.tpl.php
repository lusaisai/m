{% extends:app %}

{% block:nav %}
    <li><a href="<?php echo URL::to('home'); ?>">Home</a></li>
    <li class="active"><a href="<?php echo URL::to('artist'); ?>">Artist</a></li>
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
{% endblock %}

{% block:main %}
    <?php $dir = MAKO_APPLICATION_PATH . "/views/artist/" ?>
    <div id="searching">
        <form class="form-inline form-search" method="get">
            <input data-provide="typeahead" name="words" style="width:350px" type="text" class="typeahead form-control" placeholder="品冠">
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Search</button>
            <select class="type form-control" name="type" style="width:138px">
                <option value="artistname">Artist Name</option>
                <option value="albumname">Album Name</option>
                <option value="songname">Song Name</option>
            </select>
        </form>
    </div>
    <div id="info"></div>
    <div id="data" pagetype="artist">
        <?php include $dir . 'data.php';?>
    </div>
{% endblock %}
