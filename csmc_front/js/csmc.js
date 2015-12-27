(function(e,t,n){"$:nomunge";function f(e){e=e||location.href;return"#"+e.replace(/^[^#]*#?(.*)$/,"$1")}var r="hashchange",i=document,s,o=e.event.special,u=i.documentMode,a="on"+r in t&&(u===n||u>7);e.fn[r]=function(e){return e?this.bind(r,e):this.trigger(r)};e.fn[r].delay=50;o[r]=e.extend(o[r],{setup:function(){if(a){return false}e(s.start)},teardown:function(){if(a){return false}e(s.stop)}});s=function(){function c(){var n=f(),i=l(o);if(n!==o){a(o=n,i);e(t).trigger(r)}else if(i!==o){location.href=location.href.replace(/#.*/,"")+i}s=setTimeout(c,e.fn[r].delay)}var i={},s,o=f(),u=function(e){return e},a=u,l=u;i.start=function(){s||c()};i.stop=function(){s&&clearTimeout(s);s=n};return i}()})(jQuery,this)
function ajaxHashConfig(parameters){
	if(location.hash != ""){
		var a = location.hash.split("/?/");
		var nmc = a[0].split("#!/")[1].split("/");
		if(parameters == null && a[1] != null){
			var gparameters = new Object();
			for (var i = 0; i <  (a[1].split("/")).length; i++) {
				gparameters[i] = a[1].split("/")[i];
			};
			gparameters = JSON.stringify(gparameters);
			var config = {
			   "typee"		: 	"POST",
			   "url"		: 	_base_url,
			   "data"		: 	{
									ajax:"",
									namespace:nmc[0],
									classname:nmc[1],
									method:nmc[2],
									getParams:gparameters
								},
				"async"		: 	true,
				"dataType" 	: 	"json",
				"spawn"     :   "desktop"
			};
		} else {
			var config = {
			   "typee"		: 	"POST",
			   "url"		: 	_base_url,
			   "data"		: 	{
									ajax:"",
									namespace:nmc[0],
									classname:nmc[1],
									method:nmc[2],
									postParams:parameters
								},
				"async"		: 	true,
				"dataType" 	: 	"json",
				"spawn"     :   "desktop"
			};
		}
		
		return config;
	}
}
function ajaxRequest(config){
	buffer = "";
	loading(0);
	$.ajax({
		type: config.typee,
		url: config.url,
		data: config.data,
		async: config.async,
		dataType: config.dataType,
		success:function(buffer){
			if(buffer.spawn != ""){
				config.spawn = buffer.spawn;
			}
			if(buffer.script != ""){
				eval(buffer.script);
			}
			if(buffer.response != ""){
				$("#"+config.spawn).html(buffer.response);
			}
			if(buffer.notifications != ''){
				newNotification(buffer.notifications);
			}
		},
		error:function(errorr, b, c){
			newNotification("An error occurred check the console for more information.");
			console.log("An error occurred: "+ errorr + " " + b + " " + c);
		},
		complete:function(){
			loading(1);
		}
	});
};
$(document).ready(function(){
	$(document).off().on("click", ".desktop_option_button",function(e){
		e.preventDefault();
		/* START This code is responsible for getting the post parameters from the form button and it's values*/
		_parameters = new Object();
		var elements = this.id;
		var params = this.id;
		/* The # separates the namespace_class_method#parameter1&parameter2&...&parameterN */
		params = params.split("!");
		if(params == this.id){
			/* If there is no parameters */
			elements = elements.split("_");
		} else {
			/* If there are parameters */
			/* Get an array with all the parameters */
			params = (params[1]).split("&");
			/* The parameter is also the id of the form element which contains the value we want */
			$.each( params, function( key, value ) {
				if(value.split("=") != null){
					_parameters[value.split("=")[0]] = value.split("=")[1];	
				} else {
					_parameters[value] = $("#"+value).val();
				}
				
			});
			/* Creates a the string to be shown in the url section. Because it is a post request it will no show the parameters */
			elements = elements.split("!");
			elements = elements[0].split("_");
			_parameters = JSON.stringify(_parameters);
		}
		hash = "#!";
		$.each( elements, function( key, value ) {
			hash += "/"+value;
		});
		/* END */
		if(location.hash == hash){
			ajaxRequest(ajaxHashConfig(_parameters));
			_parameters = null;
		} else {
			location.hash = hash;
		}

		return true;
	});
	$(window).hashchange(function(){
		/*Prevents double call*/
		if (resHashChange){clearTimeout(resHashChange)};
		resHashChange = setTimeout(function(){
			if(location.hash != ""){
				ajaxRequest(ajaxHashConfig(_parameters));
				_parameters = null;
			}
		},250);
	});
	$(window).load(function(){
		/*Prevents double call*/
		if (resLoad){clearTimeout(resLoad)};
		resLoad = setTimeout(function(){
			if(location.hash != ""){
				ajaxRequest(ajaxHashConfig());
			}
		},250);
	});

});
/* Global Vars */
var _base_url 	= window.location.href.split('#')[0];
var previousScroll = 0;
var _parameters;
var resLoad;
var resHashChange;
$(window).scroll(function(){
   var currentScroll = $(this).scrollTop();
   if (currentScroll > previousScroll && previousScroll != 0){
		//Scroll down
   } else {
		//Scroll up
   }
   previousScroll = currentScroll;
});
/**
 * [Loading animation]
 */
function loading(status) 
{
  $("#loading_bar").css(
      {
        WebkitTransition: 'all 500ms ease-in-out',
        MozTransition: 'all 500ms ease-in-out',
        OTransitio: 'all 500ms ease-in-out',
        MsTransition: 'all 500ms ease-in-out',
        transition: 'all 500ms ease-in-out',
      });
  $("#main").css('opacity', '0.7');
  if(status == "0")
  {
    i = 1;
    while(i < 75)
    {
      $("#loading_bar").css("width",i-0.5+"%");
      i++;
    }
    $("#main").css('opacity', '1');
  }
  if(status == "1")
  {
    i = 75;
    while(i < 102)
    {
      $("#loading_bar").css("width",i-0.5+"%");
      i++;
    }
    $("#main").css('opacity', '1');
    setTimeout(function()
    {
      $("#loading_bar").css(
      {
        WebkitTransition: 'none',
        MozTransition: 'none',
        OTransitio: 'none',
        MsTransition: 'none',
        transition: 'none',
        width:'0%'
      });
    }, 1000); 
  } /*End if*/
};/*end funcion*/
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
function newNotification(strings){
	var numItems = $('.notification').length;
	$('#notifications').append(
	  $('<div/>')
		.attr("id", "notification"+numItems)
		.attr("class", "notification close")
		.html(strings)
	);
	setTimeout(function(){$("#notification"+numItems).addClass("open").removeClass("close");}, 100);
	setTimeout(function(){$("#notification"+numItems).addClass("close");}, 4000+numItems*1000);
	setTimeout(function(){$("#notification"+numItems).remove();}, 6000+numItems*1000);
};
