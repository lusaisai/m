$(document).ready(function(){
    var myPlaylist;
    var base = "/m/";
    var playerY = $("#the_player").position().top;

    var setPlaylistCookie = function() {
        var songs = [];
        $(".jp-playlist li[songid]").each(function () {
            songs.push($(this).attr('songid'));
        });
        $.cookie('playlist', songs.join(","), { expires: 30 });
        setTimeout( setPlaylistCookie, 30 * 1000 );
    };

    var play = function(data) {
        myPlaylist.setPlaylist(data);
        myPlaylist.option("autoPlay", true);
    };

    var loadPlaylist = function () {
        if (typeof $.cookie('playlist') != "undefined" && $.cookie('playlist') !== "") {
            var playlist = $.cookie('playlist');
            $.getJSON( base + 'playutils/songplay/' + playlist + "/0", play );
        } else {
            play([]);
        }
    };

    var setTagCanvas = function ( canvasid, tagid ) {
        $("#topSongsTags").on( 'click','li.topsongs a', function() {
            var songid = $(this).attr("songid");
            $.getJSON( base + 'playutils/songplay/' + songid + "/0", play );
        });

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
        $("#topsongs .timeline button").click(function() {
            var time = $(this).attr("time");
            var user = $("#topsongs .userstatus .active").attr("user");
            $("#topSongsTags").load(base + "home/topsongdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
        $('#topsongs .userstatus button').not(".disabled").click(function() {
            var user = $(this).attr("user");
            var time = $("#topsongs .timeline .active").attr("time");
            $("#topSongsTags").load(base + "home/topsongdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
        $("#topartists .timeline button").click(function() {
            var time = $(this).attr("time");
            var user = $("#topartists .userstatus .active").attr("user");
            $("#topArtistsTags").load(base + "home/topartistdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
        $('#topartists .userstatus button').not(".disabled").click(function() {
            var user = $(this).attr("user");
            var time = $("#topartists .timeline .active").attr("time");
            $("#topArtistsTags").load(base + "home/topartistdata/" + user + "/" + time + "/" , function () {
                $(canvasid).tagcanvas("reload");
            });
        });
    };

    myPlaylist = new jPlayerPlaylist(
            {
                jPlayer: "#jquery_jplayer_1",
                cssSelectorAncestor: "#jp_container_1"
            }, [],
            {
                supplied: "mp3,m4a",
                swfPath: "/m/assets/jplayer/js",
                wmode: "window",
                smoothPlayBar: true,
                keyEnabled: true,
                volume: 0.88,
                playlistOptions: {
                    autoPlay: false,
                    enableRemoveControls: true
                },
                ready: loadPlaylist
            }
    );

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
        $('#rplay').click(function(){
            $.getJSON( base + 'playutils/randomplay', play);
        });

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
    }

    var run= function () {
        songListToggle();
        plays();
        setTimeout( setPlaylistCookie, 30 * 1000 );
        setTagCanvas( "#topSongs", "topSongsTags" );
        setTagCanvas( "#topArtists", "topArtistsTags" );
    };

    run();
});
