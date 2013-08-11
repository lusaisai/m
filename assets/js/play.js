$(document).ready(function(){
    var myPlaylist;
    var base = "/m/";
    var playerY = $("#the_player").position().top;

    var play = function(data) {
        myPlaylist.setPlaylist(data);
        myPlaylist.option("autoPlay", true);
    };

    var loadPlaylist = function () {
        if (typeof $.cookie('playlist') !== undefined) {
            var playlist = $.cookie('playlist');
            $.getJSON( base + 'playutils/songplay/' + playlist, play );
        };
    }

    myPlaylist = new jPlayerPlaylist(
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

    var setCookie = function( songIDs, addWay ) {
        var playlist = typeof $.cookie('playlist') === undefined ? "" : $.cookie('playlist');
        switch(addWay) {
            case "replace":
                $.cookie('playlist', songIDs, { expires: 30 });
                break;
            case "add":
                $.cookie('playlist', playlist + "," + songIDs, { expires: 30 });
                break;
            default:
                $.cookie('playlist', songIDs, { expires: 30 });
        }
    }

    var playSongs = function (selector) {
        return  function() {
            var songs = new Array();

            $(this).parent().parent().find(selector).each(function(){
                if ( this.checked ){
                    songs.push($(this).attr('songid'));
                }
            });
            $.getJSON( base + 'playutils/songplay/' + songs.join(","), play );
            setCookie( songs.join(","), "replace" );
        }
    }

    function plays(){
        $('#rplay').click(function(){
            $.getJSON( base + 'playutils/randomplay', play);
        });

        $("#data").on( 'click','.album-play', playSongs("input"));

        $("#data").on( 'click','.artist-play', playSongs(".in input"));

        $("#data").on( 'click','.song-play', function(){
            var songID = $(this).attr('songid');
            $.getJSON( base + 'playutils/songplay/' + songID, play );
            setCookie( songID, "replace" );
        });

        $("#data").on( 'click','.song-add', function(){
            var songID = $(this).attr('songid');
            $.getJSON( base + 'playutils/songplay/' + songID, add);
            setCookie( songID, "add" );
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

        $(window).scroll(function() {
            if ( $(window).scrollTop() >= playerY ) {
                $("#the_player").css({ "position": "fixed", "top": "0px" })
            } else {
                $("#the_player").css({ "position": "relative" })
            };
        });
    }

    var run= function () {
        songListToggle();
        plays();
    };

    run();
});
