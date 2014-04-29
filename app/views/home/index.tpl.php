{% extends:app %}

{% block:nav %}
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
{% endblock %}

{% block:main %}
    <?php $dir = MAKO_APPLICATION_PATH . "/views/home/" ?>
    <div id="randoms">
        <?php include $dir . 'randoms.php'; ?>
    </div>
    <div id="topsongs" class="tops">
        <?php include $dir . 'topsongs.php'; ?>
    </div>
    <div id="topartists" class="tops">
        <?php include $dir . 'topartists.php'; ?>
    </div>
{% endblock %}
