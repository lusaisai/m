$(document).ready(function(){
    var myPlaylist;
    var base = "/";
    // var playerY = $("#the_player").position().top;
    var playerY = 40;
    var lrc = new Lyricer();

    var doWhenTimeUpdates = function(event) {
        var songs = [];
        var currentSong = 0;
        var currentSongId = 0;
        $(".jp-playlist li[songid]").each(function () {
            songs.push($(this).attr('songid'));
        });

        $('.jp-playlist li').each( function (index) {
            if ($(this).hasClass('jp-playlist-current')) { 
                currentSong = index; 
                currentSongId = $(this).attr("songid");
                return false;
            };
        } );

        var currentTime = event.jPlayer.status.currentTime;

        if ( currentSongId !== localStorage.getItem('currentsongid') ) {
            $.get('/playutils/showdynamiclyric/' + currentSongId, function(data) {
                lrc.setLrc(data);
                lrc.move(currentTime);
            });
        } else {
            lrc.move(currentTime);
        };

        localStorage.setItem('playlist', songs.join(","));
        localStorage.setItem('currentsong', currentSong);
        localStorage.setItem('currentsongid', currentSongId);
        localStorage.setItem('currenttime', currentTime);
    };

    var storePlayStatus = function (status) {
        return function (argument) {
            localStorage.setItem('isplay', status);
        };
    };

    var playlistTooltip = function () {
        setTimeout(function () {
            $('#the_player li').tooltip('hide');
        }, 2000);
    };

    var play = function(data) {
        myPlaylist.setPlaylist(data);
        myPlaylist.option("autoPlay", true);
        playlistTooltip();       
    };


    var readPlayStatus = function () {
        var playerID = "#jquery_jplayer_1";
        var playlist = localStorage.getItem('playlist');
        if (playlist) {
            $.getJSON( base + 'playutils/songplay/' + playlist + "/1", function (data) {
                play(data);
                $(playerID).bind( $.jPlayer.event.play, storePlayStatus(1));
                $(playerID).bind( $.jPlayer.event.pause, storePlayStatus(0));
                var index = localStorage.getItem('currentsong');
                var time = localStorage.getItem('currenttime');
                var isPlay = localStorage.getItem('isplay');
                var currentSongId = localStorage.getItem('currentsongid');
                if ( typeof index != "undefined" ) { myPlaylist.select( parseInt(index) ) };
                if ( typeof time != "undefined" ) {
                    if ( isPlay == 1 ) {
                        $(playerID).jPlayer( 'play', parseFloat(time) );
                    } else {
                        $(playerID).jPlayer( 'pause', parseFloat(time) );
                    }
                    
                };
                if (  typeof currentSongId != "undefined" ) {
                    $.get(base + 'playutils/showdynamiclyric/' + currentSongId, function(data) {
                        lrc.setLrc(data);
                    });
                };
                
                $(playerID).bind( $.jPlayer.event.timeupdate, doWhenTimeUpdates );
                
            } );
        } else {
            play([]);
            $(playerID).bind( $.jPlayer.event.play, storePlayStatus(1));
            $(playerID).bind( $.jPlayer.event.pause, storePlayStatus(0));
            $(playerID).bind( $.jPlayer.event.timeupdate, doWhenTimeUpdates );
        }
        window.mPlayList = myPlaylist; // exposed to window object for other javascripts to use
    };


    myPlaylist = new jPlayerPlaylist(
            {
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            }, [],
            {
                supplied: "m4a, mp3",
                swfPath: "/assets/jplayer/js",
                solution: 'html, flash',
                smoothPlayBar: true,
                keyEnabled: true,
                volume: 0.88,
                preload: "auto",
                playlistOptions: {
                    autoPlay: false,
                    enableRemoveControls: true
                },
                ready: readPlayStatus
            }
    );

    var add = function(data) {
        myPlaylist.add(data[0]);
        playlistTooltip();
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


    var playSongs = function (selector) {
        return  function() {
            var songs = [];

            $(this).parent().parent().find(selector).each(function(){
                if ( this.checked ){
                    songs.push($(this).attr('songid'));
                }
            });
            $.getJSON( base + 'playutils/songplay/' + songs.join(","), play );
        };
    };

    function plays(){
        $("#data").on( 'click','.album-play', playSongs("input"));

        $("#data").on( 'click','.artist-play', playSongs(".in input"));

        $("#data").on( 'click','.song-play', function(){
            var songID = $(this).attr('songid');
            $.getJSON( base + 'playutils/songplay/' + songID, play );
        });

        $("#top-song-cloud").on( 'click','text', function(){
            var songID = $(this).attr('song-id');
            $.getJSON( base + 'playutils/songplay/' + songID, play );
        });

        $("#data").on( 'click','.song-add', function(){
            var songID = $(this).attr('songid');
            $.getJSON( base + 'playutils/songplay/' + songID, add);
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

        $("#data").on( 'click','.data-pagination a[pageid]', function(){
            var pageid = $(this).attr("pageid");
            var pagetype = $("#data").attr("pagetype");

            reloadGif();
            if (history.pushState) {
                history.pushState( 
                    { fields: $("#searching form").serializeArray() }, 
                    '',  
                    base + pagetype + "/index/" + pageid + '?' + $("#searching form").serialize()
                );
            }
            $("#data").load( base + pagetype + "/load/" + pageid, $("#searching form").serialize(), function(){
                songListToggle();
            });

        });

        $("#data").on( 'click', '.playlist-play', function () {
            var songs = $(this).attr("songids");
            $.getJSON( base + 'playutils/songplay/' + songs, play );
        } )

        $("#searching form").submit(function(e){
            e.preventDefault();
            var pagetype = $("#data").attr("pagetype");
            reloadGif();
            if (history.pushState) {
                history.pushState( 
                    { fields: $("#searching form").serializeArray() }, 
                    '',  
                    base + pagetype + "/index/?" + $("#searching form").serialize()
                );
            }
            
            $("#data").load( base + pagetype + "/load", $("#searching form").serialize(), function(){
                songListToggle();
            });
            return false;
        });

        $("#randoms form").submit(function(){
            $.getJSON( base + 'home/random', $("#randoms form").serialize(), play );
            return false;
        });

        $(window).scroll(function() {
            if ( $(window).scrollTop() >= playerY && $(window).height() > $("#the_player").height() ) {
                $("#the_player").css({ "position": "fixed", "top": "0px" });
            } else {
                $("#the_player").css({ "position": "relative" });
            }
        });

        $('.jp-shuffle').click(playlistTooltip);
    }

    // connect to site every interval seconds
    var heartBeat = function(interval) {
        setInterval( function() {
            $.get('/playutils/heartbeat/');
        }, interval * 1000);
    };

    var run= function () {
        songListToggle();
        plays();
        // heartBeat(30);
    };

    run();
});
