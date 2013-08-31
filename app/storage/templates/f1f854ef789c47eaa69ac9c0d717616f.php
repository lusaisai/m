<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="<?php echo htmlspecialchars(MAKO_CHARSET, ENT_QUOTES, MAKO_CHARSET); ?>">
<title><?php echo htmlspecialchars($error['type'], ENT_QUOTES, MAKO_CHARSET); ?></title>

<style type="text/css">
	body
	{
		height:100%;
		background:#eee;
		margin:0px;
		height: 100%;
		font-size: 100%;
		color:#333;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		line-height: 100%;
	}
	table
	{
		border-spacing:0;
		border-collapse: collapse;
		border-color: #ddd;
		border-width: 0 0 1px 1px;
		border-style: solid;
	}
	td
	{
		border-color: #ddd;border-width: 1px 1px 0 0;
		border-style: solid;
		padding: 8px;
	}
	tr:nth-child(even)
	{
		background: #eee;
	}
	pre
	{
		background: #1D1F21;
		color: #ccc;
		padding: 20px;
		margin-top: 0px;
		overflow: auto;
		word-wrap: normal;
		white-space: pre;
		-moz-tab-size: 4;
		-o-tab-size: 4;
		tab-size: 4;
	}
	pre::-webkit-scrollbar
	{
		-webkit-appearance:none !important;
		width:11px !important
	}
	pre::-webkit-scrollbar
	{
		border-radius:8px !important;
		border:2px solid white !important;
		background-color:#ccc !important
	}
	pre::-webkit-scrollbar-thumb
	{
		border-radius:8px !important;
		border:2px solid white !important;
		background-color:rgba(0,0,0,.5) !important
	}
	pre div.error
	{
		background: rgba(204, 10, 20, 0.3);
		padding: 5px;
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
	#error
	{
		background:#cc0a0a;
		padding:40px;
		color:#fff;
		font-size:2.5em;
		font-weight:bold;
		text-shadow: 1px 2px 0px #333;
	}
	#error span.code
	{
		background: #F20117;
		padding: 5px;
		padding-left: 20px;
		padding-right: 20px;
		box-shadow: 1px 2px 0px 0px #75000B;
	}
	.info
	{
		background:#fff;
		padding: 20px;
		padding-left: 40px;
		padding-right: 40px;
	}
	.where
	{
		background:#eee;
		padding:10px;
		border: 1px solid #ccc;
	}
	.group
	{
		color: #333;
		background:#ccc;
		padding:20px;
		font-size:1.5em;
	}
	.group:hover
	{
		cursor: pointer;
		background: #bbb;
	}
	.group span.toggle
	{
		float: right;
	}
	.frame
	{
		padding-top: 10px;
		padding-bottom: 10px;
		border-bottom: 1px solid #ccc;
	}
	.frame:first-child
	{
		padding-top: 0px;
	}
	.frame:last-child
	{
		border: none;
		padding-bottom: 0px;
	}
</style>

</head>
<body>

<div id="error">
        <?php echo htmlspecialchars($error['type'], ENT_QUOTES, MAKO_CHARSET); ?> <?php if(isset($error['code'])): ?><span class="code"><?php echo htmlspecialchars($error['code'], ENT_QUOTES, MAKO_CHARSET); ?></span><?php endif; ?>
</div>

<div class="info">
	<p><strong><?php echo htmlspecialchars($error['message'], ENT_QUOTES, MAKO_CHARSET); ?></strong></p>

	<?php if(!empty($error['source'])): ?>
		<div class="where code"><?php echo htmlspecialchars($error['file'], ENT_QUOTES, MAKO_CHARSET); ?> on line <?php echo htmlspecialchars($error['line'], ENT_QUOTES, MAKO_CHARSET); ?></div>
		<pre><?php foreach($error['source']['code'] as $code): ?><?php if($code['current'] == $error['line']): ?><div class="error"><?php echo htmlspecialchars($code['line'], ENT_QUOTES, MAKO_CHARSET); ?></div><?php else: ?><?php echo htmlspecialchars($code['line'], ENT_QUOTES, MAKO_CHARSET); ?><?php endif; ?><?php endforeach; ?></pre>
	<?php else: ?>
		<div class="where"><?php echo htmlspecialchars($error['file'], ENT_QUOTES, MAKO_CHARSET); ?> on line <?php echo htmlspecialchars($error['line'], ENT_QUOTES, MAKO_CHARSET); ?></div>
	<?php endif; ?>
</div>

<?php if(!empty($error['backtrace'])): ?>
	<div class="group" data-target="backtrace">Backtrace <span class="toggle">+</span></div>

	<div class="info hidden" id="backtrace">
		<?php foreach($error['backtrace'] as $key => $frame): ?>
			<div class="frame">
				<p><strong><?php echo htmlspecialchars($frame['function'], ENT_QUOTES, MAKO_CHARSET); ?></strong></p>

				<?php if(!empty($frame['arguments'])): ?>
					<table width="100%">
						<?php foreach($frame['arguments'] as $k => $v): ?>
						<tr>
							<td width="20%"><?php echo htmlspecialchars($k+1, ENT_QUOTES, MAKO_CHARSET); ?></td>
							<td width="80%"><?php echo htmlspecialchars($v, ENT_QUOTES, MAKO_CHARSET, false); ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
					<br>
				<?php endif; ?>

				<?php if(!empty($frame['location'])): ?>
					<div class="where code"><?php echo htmlspecialchars($frame['location']['file'], ENT_QUOTES, MAKO_CHARSET); ?> on line <?php echo htmlspecialchars($frame['location']['line'], ENT_QUOTES, MAKO_CHARSET); ?></div>
					<pre><?php echo htmlspecialchars(trim($frame['location']['source']['code'][0]['line']), ENT_QUOTES, MAKO_CHARSET); ?></pre>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if(!empty($error['queries'])): ?>
	<div class="group" data-target="queries">Database queries <span class="toggle">+</span></div>

	<div class="info hidden" id="queries">

		<table width="100%">
			<?php foreach($error['queries'] as $query): ?>
				<tr>
					<td width="20%"><?php echo htmlspecialchars(round($query['time'], 5), ENT_QUOTES, MAKO_CHARSET); ?> seconds</td>
					<td width="80%"><?php echo htmlspecialchars($query['query'], ENT_QUOTES, MAKO_CHARSET); ?></td>
				</tr>
			<?php endforeach; ?>
		</table>

	</div>
<?php endif; ?>

<div class="group" data-target="globals">Superglobals <span class="toggle">+</span></div>

<div class="info hidden" id="globals">
	
	<?php $superglobals = array
	(
		'COOKIE'  => &$_COOKIE,
		'ENV'     => &$_ENV,
		'FILES'   => &$_FILES,
		'GET'     => &$_GET,
		'POST'    => &$_POST,
		'SERVER'  => &$_SERVER,
		'SESSION' => &$_SESSION,
	); ?>

	<?php foreach($superglobals as $name => $global): ?>

		<?php if(!empty($global)): ?>

			<h2><?php echo htmlspecialchars($name, ENT_QUOTES, MAKO_CHARSET); ?></h2>

			<table width="100%">
				<?php foreach($global as $k => $v): ?>
				<tr>
					<td width="20%"><?php echo htmlspecialchars($k, ENT_QUOTES, MAKO_CHARSET); ?></td>
					<td width="80%"><?php echo htmlspecialchars(print_r($v, true), ENT_QUOTES, MAKO_CHARSET); ?></td>
				</tr>
				<?php endforeach; ?>
			</table>

		<?php endif; ?>

	<?php endforeach; ?>

</div>

<div class="group" data-target="files">Included files <span class="toggle">+</span></div>

<div class="info hidden" id="files">
	<table width="100%">
		<?php foreach(get_included_files() as $k => $v): ?>
		<tr>
			<td width="20%"><?php echo htmlspecialchars($k + 1, ENT_QUOTES, MAKO_CHARSET); ?></td>
			<td width="80%"><?php echo htmlspecialchars($v, ENT_QUOTES, MAKO_CHARSET); ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>

<script>

	// Register onclick events

	var groups = document.getElementsByClassName('group');

	for(var i = 0; i < groups.length; i++)
	{
		groups[i].onclick = function(e)
		{
			var group = document.getElementById(e.target.getAttribute('data-target'));

			e.target.getElementsByTagName('span')[0].innerHTML = (group.style.display === 'none' ? '-' : '+');

			group.style.display = (group.style.display === 'none' ? 'block' : 'none');
		};
	}

	// Hide groups

	var hidden = document.getElementsByClassName('hidden');

	for(var i = 0; i < hidden.length; i++)
	{
		hidden[i].style.display = 'none';
	}

</script>

</body>
</html>