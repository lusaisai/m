{% extends:app %}

{% block:nav %}
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
{% endblock %}

{% block:main %}
    <?php $dir = MAKO_APPLICATION_PATH . "/views/user/" ?>
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
        <?php include $dir . 'playlist.php'; ?>
        <div class="playlistdetail"></div>
    </div>
    <script>
        $('#playlist').click(function(event) {
            $("#side li").removeClass('active');
            $(this).addClass('active');
            $('#data').load('/user/showplaylist', function() {
                $(this).append('<div class="playlistdetail"></div>');
            });

        });
        $('#info').click(function(event) {
            $("#side li").removeClass('active');
            $(this).addClass('active');
            $('#data').load('/user/updateinfo');
        });
        $('#admin').click(function(event) {
            $("#side li").removeClass('active');
            $(this).addClass('active');
            $('#data').load('/user/showadmin');
        });
    </script>
{% endblock %}

