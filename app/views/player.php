<div id="jquery_jplayer_1" class="jp-jplayer"></div>

<div id="jp_container_1" class="jp-audio">
	<div class="jp-type-playlist">
		<div class="jp-gui jp-interface">
			<ul class="jp-controls">
				<li><a href="javascript:;" class="jp-previous" tabindex="1">previous</a></li>
				<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
				<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
				<li><a href="javascript:;" class="jp-next" tabindex="1">next</a></li>
				<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
				<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
				<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
				<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
			</ul>
			<div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>

				</div>
			</div>
			<div class="jp-volume-bar">
				<div class="jp-volume-bar-value"></div>
			</div>
			<div class="jp-current-time"></div>
			<div class="jp-duration"></div>
			<ul class="jp-toggles">
				<li><a href="javascript:;" class="jp-shuffle" tabindex="1" title="shuffle">shuffle</a></li>
				<li><a href="javascript:;" class="jp-shuffle-off" tabindex="1" title="shuffle off">shuffle off</a></li>
				<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
				<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
			</ul>
		</div>
		<div class="jp-playlist">
			<ul>
				<li></li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    var myPlaylist = new jPlayerPlaylist(
            {
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            }, [], 
            {
                swfPath: "/m/assets/jplayer/js",
                supplied: "mp3",
                wmode: "window",
                smoothPlayBar: true,
                keyEnabled: true,
                volume: 1,
                playlistOptions: {
                    autoPlay: true,
                    enableRemoveControls: true
                }
            }
    );
     
    var base = "/m/index.php/";

    var play = function(data) { 
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

    function run() {
        songListToggle();
        randomPlay();
        albumPlay();    
    }
    
    run();
});
//]]>
</script>
