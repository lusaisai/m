var base = "/m/index.php/";

function randomPlay() {
    $('#rplay').click(function(){
        $.getJSON( base + 'playutils/randomplay', function(data){
            var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
            },[], {playlistOptions: {enableRemoveControls: true}});
            myPlaylist.setPlaylist(data);
            myPlaylist.play();
        });
    });
}

function albumPlay() {
    $('.album-play').click(function(){
        var id = $(this).attr('album_id');
        $.getJSON( '/mv/tool/playAlbum.php', { album_id: id }, function(data){
            var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
            });
            myPlaylist.setPlaylist(data);
            myPlaylist.play();
            $("body").animate({ scrollTop: 0 }, "slow");
        });
    });
}


$(function(){
    randomPlay();
    albumPlay();
});