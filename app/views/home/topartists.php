<h3>Top Artists</h3>
<div class="timeline btn-group" data-toggle="buttons-radio">
	<button time="week" type="button" class="btn">Week</button>
	<button time="month" type="button" class="btn">Month</button>
	<button time="all" type="button" class="btn active">Overall</button>
</div>
<div class="userstatus btn-group" data-toggle="buttons-radio">
	<button user="user" type="button" class="btn <?php if( ! Session::get( "isLogin", false ) ) echo "disabled"; ?>">My Tops</button>
	<button user="all" type="button" class="btn active">Overall</button>
</div>
<div class="cw">
	<canvas width="800" height="500" id="topArtists"></canvas>
</div>
<div id="topArtistsTags">
<?php include 'topartiststags.php'; ?>
</div>
