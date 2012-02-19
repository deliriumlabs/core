DOM={
	newObject:function(type,style){
		var newElement = document.createElement(type);
		if (style) {
			for (property in style) { 
				newElement.style[property] = style[property]; 
			}
		}
		return newElement;
	},
	removeObject:function(ele){
		try{
			var element = $(ele);
			
			if (!element){
				return 0;
			}else if (element.parentNode.removeChild(element)){
				return true;
			}else{
				return 0;
			}
		}catch(e){
			
		}
	},
	findPos:function (obj) {
		var e = obj;

		var obj = e;
		
		var curleft = 0;
		if (obj.offsetParent)
		{
			while (obj.offsetParent)
			{
				curleft += obj.offsetLeft;
				obj = obj.offsetParent;
			}
		}
		else if (obj.x)
			curleft += obj.x;
		
		var obj = e;
		
		var curtop = 0;
		if (obj.offsetParent)
		{
			while (obj.offsetParent)
			{
				curtop += obj.offsetTop;
				obj = obj.offsetParent;
			}
		}
		else if (obj.y)
			curtop += obj.y;
		
		return [curleft,curtop];
	}
}