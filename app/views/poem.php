<?php
$query = "select content, poet from poems order by rand() limit 1";
$row = Database::first($query);
if (empty($row->poet)) {
	echo $row->content;
} else {
	echo $row->content . " - " . $row->poet;
}
 ?>
 