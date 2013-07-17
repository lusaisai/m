var base = "/m/index.php/";

var play = function(data) {
            var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
            },[], {playlistOptions: {enableRemoveControls: true}});
            myPlaylist.setPlaylist(data);
            myPlaylist.play();
 };

function randomPlay() {
    $('#rplay').click(function(){
        $.getJSON( base + 'playutils/randomplay', play);
    });
}

function albumPlay() {
    $('.album-play').click(function(){
        var id = $(this).attr('album_id');
        $.getJSON( '/mv/tool/playAlbum.php', { album_id: id }, play);
    });
}


$(function(){
    randomPlay();
    albumPlay();
});