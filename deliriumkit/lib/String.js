
String.prototype.remove_tags=function(regExpTag){
	var x="";
	for(var i=0;i<arguments.length;i++){		
		if(i>0){x+="|";}
		x+=arguments[i];		
	}
	return this.replace(new RegExp(x, 'img'), '');
}

String.prototype.extract_tags=function(regExpTag){	
	if( this.match(new RegExp(regExpTag, 'img'), '')){
		return this.match(new RegExp(regExpTag, 'img'), '');
	}else{
		return "";
	}
}

String.prototype.strip_tags=function(regExpTags){
	var x="";
	for(var i=0;i<arguments.length;i++){		
		if(i>0){x+="|";}
		x+=arguments[i];		
	}
	return this.replace(new RegExp(x, 'img'), '');
}

String.prototype.format_number = function(cents) {
	num = this.toString().replace(/\$|\,/g,'');
	if(isNaN(num)){
		num = "0";
	}
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10){
		cents = "0" + cents;
	}
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++){
		num = num.substring(0,num.length-(4*i+3))+','+
		num.substring(num.length-(4*i+3));
	}
    if(cents > 0){
        return (((sign)?'':'-') + '$ ' + num + '.' + cents);
    }else{
        return (((sign)?'':'-') + '' + num);
    }

}

String.prototype.format_currency = function() {
	num = this.toString().replace(/\$|\,/g,'');
	if(isNaN(num)){
		num = "0";
	}
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10){
		cents = "0" + cents;
	}
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++){
		num = num.substring(0,num.length-(4*i+3))+','+
		num.substring(num.length-(4*i+3));
	}
	return (((sign)?'':'-') + '$ ' + num + '.' + cents);

}
