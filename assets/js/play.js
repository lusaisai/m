$(document).ready(function(){
    var myPlaylist;
    var base = "/m/";
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

        if ( currentSongId !== $.cookie('currentsongid') ) {
            $.get('/m/playutils/showdynamiclyric/' + currentSongId, function(data) {
                lrc.setLrc(data);
                lrc.move(currentTime);
            });
        } else {
            lrc.move(currentTime);
        };

        $.cookie('playlist', songs.join(","), { expires: 30, path: '/' });
        $.cookie('currentsong', currentSong, { expires: 30, path: '/' });
        $.cookie('currentsongid', currentSongId, { expires: 30, path: '/' });
        $.cookie('currenttime', currentTime, { expires: 30, path: '/' });
    };

    var setPlayCookie = function (status) {
        return function (argument) {
                $.cookie('isplay', status, { expires: 30, path: '/' });
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
        if (typeof $.cookie('playlist') != "undefined" && $.cookie('playlist') !== "") {
            var playlist = $.cookie('playlist');
            $.getJSON( base + 'playutils/songplay/' + playlist + "/1", function (data) {
                play(data);
                $(playerID).bind( $.jPlayer.event.play,  setPlayCookie(1));
                $(playerID).bind( $.jPlayer.event.pause,  setPlayCookie(0));
                var index = $.cookie('currentsong');
                var time = $.cookie('currenttime');
                var isPlay = $.cookie('isplay');
                var currentSongId = $.cookie('currentsongid');
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
            $(playerID).bind( $.jPlayer.event.play,  setPlayCookie(1));
            $(playerID).bind( $.jPlayer.event.pause,  setPlayCookie(0));
            $(playerID).bind( $.jPlayer.event.timeupdate, doWhenTimeUpdates );
        }
        window.mPlayList = myPlaylist; // exposed to window object for other javascripts to use
    };

    var canvasClick = function (argument) {
        $("#topSongsTags").on( 'click','li.topsongs a', function() {
            var songid = $(this).attr("songid");
            $.getJSON( base + 'playutils/songplay/' + songid + "/0", play );
        });
    }

    var setTagCanvas = function ( canvasid, tagid ) {
        canvasClick();

        if(! $(canvasid).tagcanvas({
                textColour : '#00f',
                textHeight : 25,
                outlineColour : '#ff9999',
                maxSpeed : 0.03,
                minSpeed : 0.005,
                minBrightness : 0.2,
                depth : 0.92,
                pulsateTo : 0.6,
                initial : [0.1,-0.1],
                decel : 0.98,
                reverse : true,
                hideTags : true,
                shadow : '#ccf',
                shadowBlur : 3,
                weight : false,
                imageScale : null,
                fadeIn : 1000
                }, tagid) ) {
            $(canvasid).hide();
        }

        $(window).blur(function(event) {
            $(canvasid).tagcanvas("pause");
        });
        $(window).focus(function(event) {
            $(canvasid).tagcanvas("resume");
        });
        $("#topsongs .timeline label").click(function() {
            var time = $(this).attr("time");
            var user = $("#topsongs .userstatus .active").attr("user");
            $("#topSongsTags").load(base + "home/topsongdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
        $('#topsongs .userstatus label').not(".disabled").click(function() {
            var user = $(this).attr("user");
            var time = $("#topsongs .timeline .active").attr("time");
            $("#topSongsTags").load(base + "home/topsongdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
        $("#topartists .timeline label").click(function() {
            var time = $(this).attr("time");
            var user = $("#topartists .userstatus .active").attr("user");
            $("#topArtistsTags").load(base + "home/topartistdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
        $('#topartists .userstatus label').not(".disabled").click(function() {
            var user = $(this).attr("user");
            var time = $("#topartists .timeline .active").attr("time");
            $("#topArtistsTags").load(base + "home/topartistdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
    };

    var playerSolution = function (argument) {
        if ( navigator.userAgent.search('Chrome/33') >= 0 ) {
            return 'flash, html'; //Chrome latest versions have seeking issues
        } else {
            return 'html, flash';
        }
    };


    myPlaylist = new jPlayerPlaylist(
            {
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            }, [],
            {
                supplied: "m4a, mp3",
                swfPath: "/m/assets/jplayer/js",
                solution: playerSolution(),
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

        $("#data").on( 'click','.pagination a[pageid]', function(){
            var pageid = $(this).attr("pageid");
            var pagetype = $("#data").attr("pagetype");

            reloadGif();
            $("#data").load( base + pagetype + "/load/" + pageid, $("#searching form").serialize(), function(){
                songListToggle();
            } );

        });

        $("#data").on( 'click', '.playlist-play', function () {
            var songs = $(this).attr("songids");
            $.getJSON( base + 'playutils/songplay/' + songs, play );
        } )

        $("#searching form").submit(function(){
            var pagetype = $("#data").attr("pagetype");
            reloadGif();
            $("#data").load( base + pagetype + "/load", $("#searching form").serialize(), function(){
                songListToggle();
            } );
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

    var run= function () {
        songListToggle();
        plays();
        setTagCanvas( "#topSongs", "topSongsTags" );
        setTagCanvas( "#topArtists", "topArtistsTags" );
    };

    run();
});
