<?php
$query = "select content from poems order by rand() limit 1";
$content = Database::column($query);
echo $content;
 ?>
 