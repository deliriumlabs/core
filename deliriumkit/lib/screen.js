function getMouseObject(e) {
  return(e? e.target: window.event.srcElement);
}
function getMouseX(e) {
  return(e? e.clientX: window.event.clientX);
}
function getMouseY(e) {
  return(e? e.clientY: window.event.clientY);
}
function pxToNumber(s) {
  return( Number( s.substring(0, s.length - 2) ) );
}

function getDocumentX(){
	if (document.documentElement && document.documentElement.clientWidth)
		// Explorer 6 Strict Mode
	{
		x = document.documentElement.clientWidth;
	}
	else if (document.body) // other Explorers
	{
		x = document.body.clientWidth;
	}
	return x;
}
function scrollX(){
	if (document.documentElement && document.documentElement.scrollWidth)
		// Explorer 6 Strict Mode
	{
		y = document.documentElement.scrollWidth;
	}
	else if (document.body) // other Explorers
	{
		y = document.body.scrollWidth;
	}
	
	return y;
}

function getDocumentY(){
	if (document.documentElement && document.documentElement.clientHeight)
		// Explorer 6 Strict Mode
	{
		y = document.documentElement.clientHeight;
	}
	else if (document.body) // other Explorers
	{
		y = document.body.clientHeight;
	}
	
	return y;
}
function scrollY(){
	if (document.documentElement && document.documentElement.scrollHeight)
		// Explorer 6 Strict Mode
	{
		y = document.documentElement.scrollHeight;
	}
	else if (document.body) // other Explorers
	{
		y = document.body.scrollHeight;
	}
	
	return y;
}