var base = "/m/index.php/";

var play = function(data) {
            var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
            },[], {playlistOptions: {enableRemoveControls: true}});
            myPlaylist.setPlaylist(data);
            myPlaylist.play();
            $("body").animate({ scrollTop: 0 }, "slow");
 };

function randomPlay() {
    $('#rplay').click(function(){
        $.getJSON( base + 'playutils/randomplay', play);
    });
}

function albumPlay() {
    $('.album-play').click(function(){
        var songs = new Array();
        
        $(this).parent().parent().find("input").each(function(){
            if ( this.checked ){
                songs.push($(this).attr('value'));
            }
        });
        
        $.getJSON( base + 'playutils/songplay/' + songs.join(","), play);
    });
}

function songListToggle() {
    $(".songs").slideToggle('slow');
    $(".song-list").click(function(){
        $(this).parent().prev().slideToggle('slow');
    });
}


$(function(){
    songListToggle();
    randomPlay();
    albumPlay();
});