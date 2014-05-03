<h3>Top Songs</h3>

<div class="timeline btn-group" data-toggle="buttons">
  <label time="week" class="btn btn-primary">
    <input  type="radio" name="options"> Week
  </label>
  <label time="month" class="btn btn-primary">
    <input type="radio" name="options"> Month
  </label>
  <label time="all" class="btn btn-primary active">
    <input type="radio" name="options"> All
  </label>
</div>

<div class="userstatus btn-group" data-toggle="buttons">
  <?php if (Session::get( "isLogin", false )): ?>
  	  <label user="user" class="btn btn-primary">
    	<input type="radio" name="options"> Mine
  	  </label>
  <?php endif ?>
  <label user="all" class="btn btn-primary active">
    <input type="radio" name="options"> All
  </label>
</div>

<div id="top-song-cloud"></div>
<script>
(function() {
  var fetchData = function() {
    var time = $("#topsongs .timeline label").attr("time");
    var user = $("#topsongs .userstatus .active").attr("user");

    $.ajax({
      dataType: "json",
      url: "/home/topsongdata/" + user + "/" + time + "/",
      success: function( data, textStatus, jqXHR) {
        setCloud(data);
      }
    });
  };

  var fontSize = d3.scale.log().range([20, 50]);

  var setCloud = function(data) {
    d3.layout.cloud()
      .size([800, 500])
      .words(data)
      .timeInterval(50)
      .text(function(d) { return d.song_name; })
      .padding(5)
      .rotate(function(d) { return ~~(Math.random() * 5) * 30 - 60; })
      .font("Impact")
      .fontSize(function(d) { return fontSize(d.cnt); })
      .on("end", draw)
      .start();
  };

  var fill = d3.scale.category20();

  var draw = function (words) {
    d3.select("#top-song-cloud")
      .html('')
      .append("svg")
      .attr("width", 800)
      .attr("height", 500)
      .append("g")
      .attr("transform", "translate(350,250)")
      .selectAll("text")
      .data(words)
      .enter()
      .append("text")
      .style("font-size", function(d) { return d.size + "px"; })
      .style("font-family", "Impact")
      .style("fill", function(d, i) { return fill(i); })
      .attr('song-id', function(d){return d.song_id;})
      .attr("text-anchor", "middle")
      .attr("transform", function(d) {
        return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
      })
      .text(function(d) { return d.text; })
      ;
  };

  fetchData();

  $("#topsongs .timeline label").click(fetchData);
  $('#topsongs .userstatus label').not(".disabled").click(fetchData);

})();
</script>