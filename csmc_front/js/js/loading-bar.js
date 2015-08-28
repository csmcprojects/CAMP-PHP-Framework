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