<?php
	$prevPageId = $pageid - 1;
	$nextPageId = $pageid + 1;
	$toalPages = ceil($count / $limit);
	$startid = $pageid - 5 > 1 ? $pageid - 5 : 1;
	$endid = $pageid + 4 < $toalPages ? $pageid + 4 : $toalPages;
?>

<div class="pagination">
	<ul>
		<?php if ($startid > 1): ?>
			<li><a href="javascript:;" pageid="1">First</a></li>
			<li><a>...</a></li>
		<?php endif ?>

		<?php for( $i = $startid; $i <= $endid; $i++ ): ?>
			<?php if ($i == $pageid): ?>
				<li class="active"><a href="javascript:;" pageid="$i"><?php echo $i?></a></li>
			<?php else: ?>
				<li><a href="javascript:;" pageid="<?php echo $i?>"><?php echo $i?></a></li>
			<?php endif ?>
		<?php endfor ?>

		<?php if ($endid < $toalPages): ?>
			<li><a>...</a></li>
			<li><a href="javascript:;" pageid="$toalPages">Last</a></li>
		<?php endif ?>
	</ul>
</div>
