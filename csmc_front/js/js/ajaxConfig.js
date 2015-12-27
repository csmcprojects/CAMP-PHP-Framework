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