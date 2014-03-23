$(document).ready(function(){
	$('input.typeahead').typeahead({
  		source: function (value, process) {
  			var type = $( ".type option:selected" ).attr('value');
  			if ( type === 'artistname' ) {
  				return $.get("/m/complete/artist", { words: value, type: "artistname" }, process );
  			} else if ( type === 'albumname' ) {
  				return $.get("/m/complete/album", { words: value, type: "albumname" }, process );
  			} else if ( type === 'songname' ) {
  				return $.get("/m/complete/song", { words: value, type: "songname" }, process );
  			};
  		},

  		matcher : function (item) {
  			return true;
  		},

  		items: 15,

      autoSelect: false
	});
});
