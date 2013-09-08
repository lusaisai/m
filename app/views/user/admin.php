<button id="db-update" class="btn btn-large btn-danger" type="button">Update Database</button>
<div id="update-data" style="margin-top: 10px;">
</div>

<script>
	$("#db-update").click( function () {
		$("#db-update").attr("disabled", "disabled");
		$("#update-data").empty();
        $("#update-data").append("<img src='/m/assets/img/ajax.gif'>");
        $("#update-data").load( "<?php echo URL::To( 'admin/update' ) ?>", function () {
        	$("#db-update").removeAttr("disabled");
        });
	} );
</script>