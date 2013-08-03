<?php

// the pagination
$prevPageId = $pageid - 1;
$nextPageId = $pageid + 1;
$toalPages = ceil($count / $limit);
$startid = $pageid - 5 > 1 ? $pageid - 5 : 1;
$endid = $pageid + 4 < $toalPages ? $pageid + 4 : $toalPages;

echo '<div class="pagination"><ul>';
if ( $startid > 1 ) {
    echo "<li><a href='javascript: void(0)' pageid='1'>First</a></li>";
    echo "<li><a>...</a></li>";
}
for( $i = $startid; $i <= $endid; $i++ ) {
    if ( $i == $pageid ) {
        echo "<li class='active'><a href='javascript: void(0)' pageid='$i'>$i</a></li>";
    } else {
        echo "<li><a href='javascript: void(0)' pageid='$i'>$i</a></li>";
    }
}
if ( $endid < $toalPages ) {
    echo "<li><a>...</a></li>";
    echo "<li><a href='javascript: void(0)' pageid='$toalPages'>Last</a></li>";
}
echo '</ul></div>';

?>
