$(document).ready(function(){
	$(document).off().on("click", ".form_button",function(e){
		e.preventDefault();
		_parameters = new Object();
		var elements = this.id;
		var params = this.id;
		params = params.split("#");
		if(params == this.id){
			elements = elements.split("_");
		} else {
			params = (params[1]).split("&");
			$.each( params, function( key, value ) {
				_parameters[value] = $("#"+value).val();
			});
			elements = elements.split("#");
			elements = elements[0].split("_");
			_parameters = JSON.stringify(_parameters);
		}
		hash = "#!";
		$.each( elements, function( key, value ) {
			hash += "/"+value;
		});
		if(location.hash == hash){
			ajaxRequest(ajaxHashConfig(_parameters));
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