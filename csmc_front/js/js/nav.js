$(document).ready(function(){
	$(".nav_option_button").off().click(function(){
		elements = this.id.split("_");
		var hash = "#!"	
		$.each( elements, function( key, value ) {
			hash += "/"+value;
		});
		if(location.hash == hash){
			ajaxRequest(ajaxHashConfig());
		} else {
			location.hash = hash;
		}
		navFAR("hidden", "show");
	});
	$('.nob').off().click(function(){
	  if($("nav").hasClass('show')){
		navFAR("hidden", "show");		
	  } else if($("nav").hasClass('hidden')){
		navFAR("show", "hidden");
	  } else {
		navFAR("show", "hidden");
	  }
	  return;
	});
	$("#desktop").off().click(function(){
	  if($("nav").hasClass('show')){
		navFAR("hidden", "show");
	  }
	});
	return;
});
function navFAR(a, r){
	switch(a){
		case "show": $("nav").fadeIn().addClass(a).removeClass(r);$("header").fadeIn();break;
		case "hidden": $("nav").fadeOut().addClass(a).removeClass(r);break;
	}
	document.cookie = 'csmc_native_nav_status='+a+';';
	return;
}