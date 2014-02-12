<?php foreach ($data as $artist): ?>
    <?php $i = 1 ?>
    <div id="<?php echo $artist["artist_id"] ?>" class="album panel panel-primary">
        <div class="panel-heading"><h3><?php echo $artist["artist_name"] ?></h3></div>
        <div class="panel-body">
        <div>
            <a data-lightbox="lightbox-image" href="<?php echo "/music/{$artist["artist_name"]}/{$artist["image_name"]}" ?>">
                <img src="<?php echo "/music/{$artist["artist_name"]}/{$artist["image_name"]}" ?>" class="img-rounded album-image">
            </a>
        </div>
        <div id="<?php echo "accordion{$artist["artist_id"]}" ?>" class="panel-group slide">
            <?php foreach ($artist["albums"] as $album): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a class="panel-title" data-toggle="collapse" data-parent="<?php echo "#accordion{$artist["artist_id"]}" ?>" href="<?php echo "#collapse{$album["album_id"]}" ?>"><?php echo $album["album_name"] ?></a>
                    </div>
                    <div id="<?php echo "collapse{$album["album_id"]}" ?>" class="panel-collapse collapse <?php if ($i==1) echo "in"; ?>">
                        <div class="panel-body songs">
                            <table class="table table-condensed table-bordered table-hover table-striped">
                                <?php foreach ($album["songs"] as $song): ?>
                                    <tr>
                                        <td>
                                            <label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song["song_id"] ?>">
                                                <?php echo $song["song_name"] ?>
                                                <?php if ( $song["is_hot"] ): ?>
                                                    <span class="badge badge-hot">Hot</span>
                                                <?php endif ?>
                                            </label>
                                        </td>
                                        <td style="text-align:center"><button class="btn btn-default btn-xs song-play" type="button" songid="<?php echo $song["song_id"] ?>"><span class="glyphicon glyphicon-headphones"></span></button></td>
                                        <td style="text-align:center"><button class="btn btn-default btn-xs song-add" type="button" songid="<?php echo $song["song_id"] ?>"><span class="glyphicon glyphicon-plus"></span></button></td>
                                   </tr>
                                <?php endforeach ?>
                            </table>
                            <div class="btn-group">
                                <button class="btn btn-default reverse-check">Reverse Check</button>
                                <button class="btn btn-default check-all">Check All</button>
                                <button class="btn btn-default uncheck-all">Uncheck All</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++ ?>
            <?php endforeach ?>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary song-list"><span class="glyphicon glyphicon-list"></span></button>
            <button artist_id="<?php echo $artist["artist_id"] ?>" class="btn btn-primary artist-play"><span class="glyphicon glyphicon-music"></span> Play</button>
        </div>
        </div>
    </div>
<?php endforeach ?>

<?php include dirname(__FILE__) . '/../pagination.php' ?>

<script><?php include dirname(__FILE__) . '/../common.js' ?></script>
