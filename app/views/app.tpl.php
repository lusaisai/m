<?php $dir = MAKO_APPLICATION_PATH . "/views/" ?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" type="image/ico" href="/assets/img/favicon.ico"/>
        <title><?php include $dir . 'poem.php';?></title>
        <?php include $dir . 'assets.php';?>
    </head>
    <body>
        <div class="container">
            <?php include $dir.'info.php'; ?>
            <ul class="nav nav-pills">
                {{block:nav}}{{endblock}}
            </ul>
            <div class="row">
                <div class="col-md-3">
                    <?php include $dir.'player.html'; ?>
                </div>
                <div class="col-md-8">
                    {{block:main}}{{endblock}}
                </div>
            </div>
        </div>
        <?php include $dir . 'common.php' ?>
        <script><?php include $dir . 'common.js' ?></script>
    </body>
</html>
