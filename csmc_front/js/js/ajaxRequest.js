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