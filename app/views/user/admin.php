<button id="db-update" class="btn btn-large btn-danger" type="button">Update Database</button>
<button id="lyric-update" class="btn btn-large btn-danger" type="button">Update Lyrics</button>
<div id="update-data" style="margin-top: 10px;">
</div>

<script>
	$("#db-update").click( function () {
		$(this).attr("disabled", "disabled");
		$("#update-data").empty();
        $("#update-data").append("<img src='/m/assets/img/ajax.gif'>");
        $("#update-data").load( "<?php echo URL::To( 'admin/update' ) ?>", function () {
        	$("#db-update").removeAttr("disabled");
        });
	} );
	$("#lyric-update").click( function () {
		$(this).attr("disabled", "disabled");
		$("#update-data").empty();
        $("#update-data").append("<img src='/m/assets/img/ajax.gif'>");
        $("#update-data").load( "<?php echo URL::To( 'admin/lyric' ) ?>", function () {
        	$("#lyric-update").removeAttr("disabled");
        });
	} );
</script>