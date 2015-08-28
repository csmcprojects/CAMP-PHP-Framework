$(window).scroll(function(){
   var currentScroll = $(this).scrollTop();
   if (currentScroll > previousScroll && previousScroll != 0){
		//Scroll down
   } else {
		//Scroll up
   }
   previousScroll = currentScroll;
});