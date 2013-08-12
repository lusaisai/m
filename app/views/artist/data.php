<?php foreach ($data as $artist): ?>
    <div id="<?php echo $artist["artist_id"] ?>" class="album">
        <blockquote>
            <h3><?php echo $artist["artist_name"] ?></h3>
        </blockquote>
        <div><img src="<?php echo "/music/{$artist["artist_name"]}/{$artist["image_name"]}" ?>" class="img-rounded album-image"></div>
        <div id="<?php echo "accordion{$artist["artist_id"]}" ?>" class="slide accordion">
            <?php foreach ($artist["albums"] as $album): ?>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="<?php echo "#accordion{$artist["artist_id"]}" ?>" href="<?php echo "#collapse{$album["album_id"]}" ?>"><?php echo $album["album_name"] ?></a>
                    </div>
                    <div id="<?php echo "collapse{$album["album_id"]}" ?>" class="accordion-body collapse">
                        <div class="accordion-inner songs">
                            <table class="table table-bordered table-hover table-condensed">
                                <?php foreach ($album["songs"] as $song): ?>
                                    <tr>
                                        <td><label class="checkbox"><input type="checkbox" checked="checked" songid="<?php echo $song["song_id"] ?>"><?php echo $song["song_name"] ?></label></td>
                                        <td style="text-align:center"><button class="btn btn-mini song-play" type="button" songid="<?php echo $song["song_id"] ?>"><i class="icon-headphones"></i></button></td>
                                        <td style="text-align:center"><button class="btn btn-mini song-add" type="button" songid="<?php echo $song["song_id"] ?>"><i class="icon-plus"></i></button></td>
                                   </tr>
                                <?php endforeach ?>
                            </table>
                            <div class="btn-group">
                                <button class="btn reverse-check">Reverse Check</button>
                                <button class="btn check-all">Check All</button>
                                <button class="btn uncheck-all">Uncheck All</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="btn-group">
            <button class="btn btn-primary song-list"><i class="icon-list icon-white"></i></button>
            <button artist_id="<?php echo $artist["artist_id"] ?>" class="btn btn-primary artist-play"><i class='icon-music icon-white'></i> Play</button>
        </div>
    </div>
<?php endforeach ?>

<?php include dirname(__FILE__) . '/../pagination.php' ?>
