<h3>Top Songs</h3>
<div id="timeline" class="btn-group" data-toggle="buttons-radio">
	<button time="week" type="button" class="btn">Week</button>
	<button time="month" type="button" class="btn">Month</button>
	<button time="all" type="button" class="btn active">Overall</button>
</div>
<div id="userstatus" class="btn-group" data-toggle="buttons-radio">
	<button user="user" type="button" class="btn <?php if( ! Session::get( "isLogin", false ) ) echo "disabled"; ?>">My Tops</button>
	<button user="all" type="button" class="btn active">Overall</button>
</div>
<canvas width="800" height="500" id="topSongs"></canvas>
<div id="topSongsTags">
<?php include 'topsongstags.php'; ?>
</div>
