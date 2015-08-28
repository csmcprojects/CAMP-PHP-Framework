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