$(document).ready(function() {

	// Expand Panel
	$("#jqlopen").click(function(){
		$("div#jqlpanel").slideDown("slow");
		$('#hide_content').fadeIn('fast');

	});

	// Collapse Panel
	$("#jqlclose").click(function(){
		$("div#jqlpanel").slideUp("slow");
		$('#hide_content').fadeOut('fast');
	});

	// Collapse Panel
	$("#hide_content").click(function(){
		$("div#jqlpanel").slideUp("slow");
		$('#hide_content').fadeOut('fast');
		$("#toggle a").toggle();
	});

	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});
	
	$('.avatar').fadeTo('fast', 0.4);
	$('.avatar').mouseenter(function() {
		$('.avatar').fadeTo('slow', 1);
	});

	$('.avatar').mouseleave(function() {
		$('.avatar').fadeTo('slow', 0.4);
	});
	
	$("#admin").tooltip({tip:"#admin_links", position:"center left", effect:"fade", fadeInSpeed:500, fadeOutSpeed:500, offset:[20, -10]}); 
	
	$("#pm_info").tooltip({tip:"#pm_infos", position:"center left", effect:"fade", fadeInSpeed:500, fadeOutSpeed:500, offset:[0, -10]}); 
	
	$("#pm_alert").tooltip({tip:"#pm_alert_info", position:"top center", effect:"fade", fadeInSpeed:500, fadeOutSpeed:500, offset:[-15, 0]}); 
	
	$("#scoresinfo").tooltip({tip:"#scores_info", position:"center left", effect:"fade", fadeInSpeed:500, fadeOutSpeed:500, offset:[0, -10]}); 
	
	$("#submititem").tooltip({tip:"#submit_links", position:"center left", effect:"fade", fadeInSpeed:500, fadeOutSpeed:500, offset:[0, -10]}); 
	
});

function pm_alert_off() {
	var ablauf = new Date();
	var datum = ablauf.getTime() + (365 * 24 * 60 * 60 * 1000);
	ablauf.setTime(datum);
	document.cookie =  "pm_alert=0; path=/; expires=" + ablauf.toGMTString();
}

function pm_alert_on() {
	var ablauf = new Date();
	var datum = ablauf.getTime() + (365 * 24 * 60 * 60 * 1000);
	ablauf.setTime(datum);
	document.cookie =  "pm_alert=1; path=/; expires=" + ablauf.toGMTString();
}