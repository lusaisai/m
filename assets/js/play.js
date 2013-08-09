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

    var play = function(data) {
        myPlaylist.setPlaylist(data);
        $("body").animate({ scrollTop: 0 }, "slow");
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

    var playSongs = function (selector) {
        return  function() {
            var songs = new Array();

            $(this).parent().parent().find(selector).each(function(){
                if ( this.checked ){
                    songs.push($(this).attr('songid'));
                }
            });
            $.getJSON( base + 'playutils/songplay/' + songs.join(","), play );
        }
    }

    function plays(){
        $('#rplay').click(function(){
            $.getJSON( base + 'playutils/randomplay', play);
        });

        $("#data").on( 'click','.album-play', playSongs("input"));

        $("#data").on( 'click','.artist-play', playSongs(".in input"));

        $("#data").on( 'click','.song-play', function(){
            $.getJSON( base + 'playutils/songplay/' + $(this).attr('songid'), play );
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
