// Textarea Autoclear
jQuery(function(){
	jQuery.unobtrusivelib();
});

// About
(function($){
  $(".open").click(function () {
    $("#aboutMoreBox").toggle();
  });
})(jQuery);

// Visitor keypress
(function($){
  $('#visitorpad').keyup(function(event) {
    $("#uWriting").show();
  });
    
  $('#visitorpad').blur(function() {
    $("#uWriting").hide();
  });
})(jQuery);

// Antonym typing
(function($){
  $.antonyTyping = function(settings) {
    var $visitorpad = $("#visitorpad"),
      $antonypad = $("#antonypad .content"),
      request = false,
      requestTimer,
      writeTimer,
      currentText = "",
      writedText = $antonypad.text(),
      lastTypedText = "",
      isTyping = false,
      settings = $.extend({
        "onStartTyping": function(){},
        "onStopTyping": function(){}
      }, settings);
    
    requestTimer = window.setInterval(function(){
      
      // Waiting for request or No new text? Do nothing.
      if ( request || lastTypedText == (lastTypedText = $visitorpad.val()) ) {
        return;
      }
      
      // Translation request
      request = true;
      $.get( "convert.php", { "t": encodeURIComponent(lastTypedText) }, function(data){
          currentText = data;
          request = false;
        });
    }, 300);
    
    // Type first letter.
    initNextTyping();
    
    // Type or delete a letter after a delay.
    function initNextTyping(delay) {
      
      window.setTimeout(function(){
        
        var newDelay = null;
        
        // No changes? Do nothing.
        if (writedText === currentText) {
          initNextTyping(delay);
          isTyping = false;
          settings.onStopTyping();
          return;
        }
        
        // Start typing
        if (!isTyping) {
          isTyping = true;
          settings.onStartTyping();
        }
        
        // The user is deleting? Antony too.
        if (writedText !== currentText.slice(0, writedText.length)) {
          writedText = writedText.slice(0,-1);
          newDelay = 70; // Deleting speed is high and regular
          
        // Normal typing
        } else {
          writedText = currentText.slice(0, writedText.length+1);
        }
        
        // Fill antony pad
        $antonypad.html(writedText);
        
        // Call itself
        initNextTyping(newDelay);
        
      }, delay || getRandomInt(5,20)*10); // Typing speed is random
    };
    
    // Thanks Mozilla : https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Global_Objects/Math/random#section_5
    function getRandomInt(min, max) {  
      return Math.floor(Math.random() * (max - min + 1)) + min;
    };
  };
})(jQuery);

// Antony typing init
(function($){
  $.antonyTyping({
    "onStartTyping": function(){
      $("#aWriting").show();
    },
    "onStopTyping": function(){
      $("#aWriting").hide();
    }
  });
})(jQuery);
