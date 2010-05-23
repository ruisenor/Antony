// About
(function($){
  $("#about").click(function () {
    $("#aboutMore").toggle();
  });
})(jQuery);

// Keypress
(function($){
  $('#visitorpad').keyup(function(event) {
    $("#uWriting").show();
  });
    
  $('#visitorpad').blur(function() {
    $("#uWriting").hide();
  });
})(jQuery);

// Antonym translation
(function($){
  var $visitorpad = $("#visitorpad"),
      $antonypad = $("#antonypad"),
      request = false,
      timer;
  
  timer = window.setInterval(function(){
    if (request) { return; }
    
    $.get("convert.php?t="+$visitorpad.val(), function(data){
      $antonypad.val(data+"…");
      request = false;
    });
  }, 1000);
})(jQuery);
