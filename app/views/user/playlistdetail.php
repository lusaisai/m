<div class="songs">
    <table class="table table-bordered table-hover table-condensed">
        <?php foreach ($songs as $song): ?>
            <tr>
                <td><label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song->id ?>"><?php echo $song->song_name ?></label></td>
                <td><?php echo $song->artist_name; ?></td>
                <!-- <td><?php echo $song->album_name; ?></td> -->
                <td style="text-align:center"><button class="btn btn-default btn-xs song-play" type="button" songid="<?php echo $song->id ?>"><span class="glyphicon glyphicon-headphones"></span></button></td>
                <td style="text-align:center"><button class="btn btn-default btn-xs song-add" type="button" songid="<?php echo $song->id ?>"><span class="glyphicon glyphicon-plus"></span></button></td>
            </tr>
        <?php endforeach ?>
    </table>
    <div class="btn-group">
        <button class="btn btn-default reverse-check">Reverse Check</button>
        <button class="btn btn-default check-all">Check All</button>
        <button class="btn btn-default uncheck-all">Uncheck All</button>
    </div>
    <div class="btn-group">
        <button class="btn btn-primary album-play"><span class="glyphicon glyphicon-music"></span> Play</button>
        <button class="btn btn-danger playlist-delete"><span class="glyphicon glyphicon-remove"></span> Delete</button>
        <button class="btn btn-warning playlist-rename"><span class="glyphicon glyphicon-edit"></span> Rename</button>
    </div>
</div>
<script>
    $(".playlist-delete").click(function () {
        var JActive = $('.playlist .btn-success');
        var id = JActive.attr('playlistid');
        bootbox.confirm("You are going to delete playlist " + JActive.closest('.playlist').find('.playlist-name').text() + ", Please confirm.", function(result) {
            if (result) {
                $.get( '/m/user/deleteplaylist/' + id , function(data) {
                    $('#data').load('/m/user/showplaylist');
                });
            };
        }); 
    });
    $(".playlist-rename").click(function () {
        var JActive = $('.playlist .btn-success');
        var playlistid = JActive.attr('playlistid');
        var songids = JActive.attr('songids');
        bootbox.prompt("Please enter the new playlist name ", function(result) {
            if (result !== null) {
                $.get( '/m/user/saveplaylist/' + songids + '/' + result + '/' + playlistid , function(data) {
                    $('#data').load('/m/user/showplaylist');
                });
            };
        }); 
    });
</script>