$(document).ready(function() {

	// about
	$("#about").click(function () {
		$("#aboutMore").toggle();
	});

	// keypress

$('#visitorpad').keyup(function(event) {
	$("#uWriting").show();
	});
	
$('#visitorpad').blur(function() {
	$("#uWriting").hide();
	});	
});


