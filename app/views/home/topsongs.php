<h3>Top Songs</h3>

<div class="timeline btn-group" data-toggle="buttons">
  <label time="week" class="btn btn-primary">
    <input  type="radio" name="options"> Week
  </label>
  <label time="month" class="btn btn-primary">
    <input type="radio" name="options"> Month
  </label>
  <label time="all" class="btn btn-primary active">
    <input type="radio" name="options"> All
  </label>
</div>

<div class="userstatus btn-group" data-toggle="buttons">
  <?php if (Session::get( "isLogin", false )): ?>
  	  <label user="user" class="btn btn-primary">
    	<input type="radio" name="options"> Mine
  	  </label>
  <?php endif ?>
  <label user="all" class="btn btn-primary active">
    <input type="radio" name="options"> All
  </label>
</div>

<div class="cw">
	<canvas width="800" height="500" id="topSongs"></canvas>
</div>
<div id="topSongsTags">
<?php include 'topsongstags.php'; ?>
</div>
