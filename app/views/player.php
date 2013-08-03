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
                supplied: "mp3,m4a",
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
     
    var base = "/m/";

    var play = function(info) { 
        return function(data){
            myPlaylist.setPlaylist(data);
            myPlaylist.play();
            $("body").animate({ scrollTop: 0 }, "slow", function(){
                var JInfo = $("<div class='alert alert-success'> Now playing " + info + " ...</div>");
                var JBox = $('#info');
                JBox.empty();
                JInfo.hide().appendTo(JBox).show('slow');
            });
        };
    };
    
    var add = function(data) {
        myPlaylist.add(data[0]);
    };
    
    var reloadGif = function(){
        $("#data").empty();
        $("#data").append("<img src='" + base + "assets/img/ajax.gif'>");
    };

    var songListToggle = function () {
        $(".slide").slideToggle('slow');
        $(".song-list").click(function(){
            $(this).parent().prev().slideToggle('slow');
        });
    };
    
    function plays(){
        $('#rplay').click(function(){
            $.getJSON( base + 'playutils/randomplay', play("randomly"));
        });
        
        $("#data").on( 'click','.album-play', function(){
            var songs = new Array();

            $(this).parent().parent().find("input").each(function(){
                if ( this.checked ){
                    songs.push($(this).attr('songid'));
                }
            });
            
            var albumName = $(this).parent().parent().find("blockquote p").first().text();
            if ( albumName !== "" ) albumName = "album " + albumName;
            $.getJSON( base + 'playutils/songplay/' + songs.join(","), play(albumName));
        });
        
        $("#data").on( 'click','.song-play', function(){
            $.getJSON( base + 'playutils/songplay/' + $(this).attr('songid'), play("song " + $(this).attr('songname')));
        });
        
        $("#data").on( 'click','.song-add', function(){
            $.getJSON( base + 'playutils/songplay/' + $(this).attr('songid'), add);
        });
        
        $("#data").on( 'click','.reverse-check', function(){
            $(this).closest("div.songs").find("input[type='checkbox']").each(function(){
                $(this).prop( "checked", !$(this).prop("checked") );
            });
        });
        
        $("#data").on( 'click','.check-all', function(){
            $(this).closest("div.songs").find("input[type='checkbox']").prop("checked", true);
        });
        
        
        $("#data").on( 'click','.uncheck-all', function(){
            $(this).closest("div.songs").find("input[type='checkbox']").prop("checked", false);
        });
        
        $("#data").on( 'click','.pagination a[pageid]', function(){
            var pageid = $(this).attr("pageid");
            var pagetype = $("#data").attr("pagetype");
            
            reloadGif();
            $("#data").load( base + pagetype + "/load/" + pageid, $("#searching form").serialize(), function(){
                songListToggle();
                $("body").animate({ scrollTop: 0 }, "slow");
            } );
            
        });
        
        $("#searching form").submit(function(){
            var pagetype = $("#data").attr("pagetype");
            reloadGif();
            $("#data").load( base + pagetype + "/load", $("#searching form").serialize(), function(){
                songListToggle();
                $("body").animate({ scrollTop: 0 }, "slow");
            } );
            return false;
        });
    }

    var run= function () {
        songListToggle();
        plays();   
    };
    
    run();
});
//]]>
</script>
