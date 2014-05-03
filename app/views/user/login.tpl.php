{% extends:app %}

{% block:nav %}
    <li><a href="<?php echo URL::to('home/index'); ?>">Home</a></li>
    <li><a href="<?php echo URL::to('artist/index'); ?>">Artist</a></li>
    <li><a href="<?php echo URL::to('album/index'); ?>">Album</a></li>
    <li><a href="<?php echo URL::to('song/index'); ?>">Song</a></li>
    <li class="active pull-right"><a href="<?php echo URL::to('user/login'); ?>">Login</a></li>          
{% endblock %}

{% block:main %}
    <?php $dir = MAKO_APPLICATION_PATH . "/views/user/" ?>
    <?php if ($errors != ""): ?>
        <div class="alert alert-danger"><?php echo $errors; ?></div>
    <?php endif ?>
    <div id="data" pagetype="login">
        <?php include $dir . 'loginform.php';?>
    </div>
{% endblock %}

