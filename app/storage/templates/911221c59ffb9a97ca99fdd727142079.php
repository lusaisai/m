<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="<?php echo htmlspecialchars(MAKO_CHARSET, ENT_QUOTES, MAKO_CHARSET); ?>">
<title>404 Not Found</title>

<style type="text/css">
body
{
        height:100%;
        background:#eee;
        padding:0px;
        margin:0px;
        height: 100%;
        font-size: 100%;
        color:#333;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        line-height: 100%;
}
a
{
        color:#0088cc;
        text-decoration:none;
}
a:hover
{
        color:#005580;
        text-decoration:underline;
}
h1
{
        font-size: 4em;
}
small
{
        font-size: 0.7em;
        color: #999;
        font-weight: normal;
}
hr
{
        border:0px;
        border-bottom:1px #ddd solid;
}
#message
{
        width: 700px;
        margin: 15% auto;
}
#back-home
{
        bottom:0px;
        right:0px;
        position:absolute;
        padding:10px;
}
</style>

</head>
<body>

<div id="message">
<h1>404 <small>Not Found</small></h1>
<hr>
<p>The page you requested could not be found. It may have been moved or deleted.</p>
</div>

<div id="back-home"><small>Would you like to <a href="<?php echo htmlspecialchars(URL::base(), ENT_QUOTES, MAKO_CHARSET); ?>">go back home</a>?</small></div>

</body>
</html>