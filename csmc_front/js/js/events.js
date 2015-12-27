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