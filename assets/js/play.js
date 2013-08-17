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

        $("#searching form").submit(function(){
            var pagetype = $("#data").attr("pagetype");
            reloadGif();
            $("#data").load( base + pagetype + "/load", $("#searching form").serialize(), function(){
                songListToggle();
            } );
            return false;
        });

        $("#randoms form").submit(function(){
            $.getJSON( base + 'home/random', $("#randoms form").serialize(), function (data) {
                $.getJSON( base + 'playutils/songplay/' + data.ids + "/0", play );
            } );
            return false;
        });

        $(window).scroll(function() {
            if ( $(window).scrollTop() >= playerY ) {
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
    };

    run();
});
