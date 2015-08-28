function ajaxHashConfig(parameters){
	if(location.hash != ""){
		a = location.hash.split("/?/");
		nmc = a[0].split("#!/")[1].split("/");
		config = {
		   "typee"		: 	"POST",
		   "url"		: 	_base_url,
		   "data"		: 	{
								ajax:"",
								namespace:nmc[0],
								classname:nmc[1],
								method:nmc[2],
								params:parameters
							},
			"async"		: 	true,
			"dataType" 	: 	"json",
			"spawn"     :   "desktop"
		};
		return config;
	}
}