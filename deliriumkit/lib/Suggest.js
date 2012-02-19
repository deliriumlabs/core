/*
 *  Ultima modificacion :Wed 22 Apr 2009 11:05:00 AM CDT
 */
var DS_list=[];
function mvcSuggest(mvc,input,_callback,_allow_new){
	
	if(typeof mvc=='undefined'){
		alert('MVC Incorrecto');
	}
	
	if(typeof _allow_new=='undefined'){
		_allow_new=true;
	}		
	
	var _callback = (typeof _callback =="string")? eval(_callback) : _callback;

	if(typeof _callback !='function'){
		_callback=function(){return true;}
	}
	

	if(!$('_ds_'+input.id)){
		DS_list['_objDs_'+input.id]= new Suggest(
								{
									id:input.id,
									url:'raw.php',
									params:'do='+mvc,
									callback:_callback,
									allow_new:_allow_new
								}
							);
	}
	return DS_list['_objDs_'+input.id];		
}

var Suggest=function(options){
	var me=this;
	this.item_highlighted=0;
	this.id=null;
	this.items=[];	
	this.options={		
		url:'',
		params:'',
		timeout:3000,
		callback:function(){return true;},
		last_query:'',
        vars:'',
		allow_new:true,
        callback_extra:''
	}
    this.setVars = function(vars){
        this.options.vars = vars;
    }
    this.setCallBackExtra = function(vars){
        this.options.callback_extra = vars;
    },
	this.go=function(query){	
		var me=this
		if(query==''){
			return false;
		}
		//this.item_highlighted=0;
		//this.highlight_clear();
        this.highlight(this.last_item_highlighted);
		if(this.options.last_query==query){
			this.change_display(me);	
			//this.highlight(1);		
            this.highlight(this.last_item_highlighted);
		}else{			;	
			//var id=AJAXRequestCount;
			//AJAXRequestCount++;
		
			//AJAXRequest[id]=new dk.AJAX({write_to:'',url:this.options.url});
			//AJAXRequest[id].setParams(this.options.params+'&query='+query+'&'+this.options.vars);
			//AJAXRequest[id].setOnComplete(function(){
				//try{
				//AJAXRequest[id].responseText=(typeof AJAXRequest[id].responseText!='undefined')?AJAXRequest[id].responseText:'';
				//me.show(AJAXRequest[id].responseText,me);
				//}catch(e){}
				//AJAXRequest.splice(id,1);
				//AJAXRequestCount--;
			//})
			this.options.last_query=query;
			//AJAXRequest[id].doGet();
            mvcPost(this.options.url,this.options.params+'&query='+encode(query)+'&'+this.options.vars,'',function(response){me.show(response, me);});
			
		}
		this.TimeOutStop();
	}
	
	this.change_display=function(me){
		if(me._DS_div.style.display=="none"){
			me._DS_div.style.display="block";						
		}
		//VARIABLES PARA CORDENADAS DE DESPLIEGUE
		var pos=DOM.findPos($(me.options.id));
		
		//POSICIONAR EL DIV
		//me._DS_div.style.left = pos[0] + 'px';
		//me._DS_div.style.top = (pos[1]+$(me.options.id).offsetHeight) + 'px';	
	}
	this.show=function(results,me){				
		me.change_display(me);
		results=parseJson(results);		
		me.ul=DOM.newObject('ul');
		me.className="autosuggest";		
		me.cc.innerHTML="";		
		me.cc.appendChild(me.ul);
				
		for(var i=0;i<results.length;i++){
			result=results[i];
			id=result.id;
			val=result.val;
			info=result.info;
			
			var li= DOM.newObject('li');
			var a=DOM.newObject('a');
			var span=DOM.newObject('span');
			a.setAttribute("href","javascript:void(0);");
			a.setAttribute("_value",val);
			a.setAttribute("_info",info);
			a.setAttribute("_id",id);
			a.setAttribute("class",'autosuggest_result');
			
			a.id=i+1;
			me.items[a.id]=result;
			
			span.innerHTML=val+" <span class=\"autosuggest_info\">"+info+"</span>";
			a.appendChild(span);
			a.onmouseover=function(){
							me.highlight(this.id);
						}

			a.onclick=function(){
							me.setValue();
						}			

			li.appendChild(a);
			me.ul.appendChild(li);
		}
        try{
		this.highlight(1);
        }catch(e){debug(e);}
		me.TimeOut=setTimeout(function () { me.clear() }, me.options.timeout);
		
	}
	this.onKeyPress=function(e){		
		var me = this;
		$(this.options.id).onblur=function(e){return true;}
		this.TimeOutStop();
		var key = (window.event) ? window.event.keyCode : e.keyCode;

		var continue_bubble=true;
		switch(key){
			case KEYS.RETURN:
				if (this.options.last_query != $value(this.options.id) || this._DS_div.style.display!="none"&& !this.options.allow_new) {
					this.setValue();
					this.clear();
                    var i;
                    var field = $(this.options.id);
                    for (i = 0; i < field.form.elements.length; i++){
                        if (field == field.form.elements[i]){
                            break;
                        }
                    }
                    var ok = false;
                    while(i < field.form.elements.length && ok !=true){
                        i = (i + 1) % field.form.elements.length;
                        
                        var elem = field.form.elements[i];
                        var check = false;
                        switch(elem.type){
                            case "text":
                                check = true;
                                break;
                            case "checkbox":
                                check = true;
                                break;
                            case "radio":
                                check = true;
                                break;
                            case "select":
                                check = true;
                                break;
                            case "button":
                                check = true;
                                break;
                            case "submit":
                                check = true;
                                break;
                            case "textarea":
                                check = true;
                                break;
                            default:
                                check = false;
                                break;
                        }
                        if(check == true && elem.style.display != "none"){
                            elem.select();
                            elem.focus();
                            ok = true;
                        }
                    }
                     
				}								
				continue_bubble=false;
				break;

			case KEYS.TAB:
				if (this.options.last_query != $value(this.options.id) || this._DS_div.style.display!="none"&& !this.options.allow_new) {										
					this.setValue();
					this.clear();
				}								
				//continue_bubble=false;
				//me.onBlur();
				break;

			case KEYS.ESC:
				this.clear();
				break;
			default:
				//$(this.options.id).onblur=function(e){return me.onBlur();}
				break;
		}
		return continue_bubble;
	}
	this.onKeyUp=function(e){
		this.TimeOutStop();
		var key = (window.event) ? window.event.keyCode : e.keyCode;
		
		var continue_bubble=true;
		switch(key){
			case KEYS.UP:
				this.highlight_changed(key);
				continue_bubble=false;
				break;
				
			case KEYS.DOWN:				
				this.highlight_changed(key);
				continue_bubble=false;
				break;		
			case KEYS.RETURN:				
				continue_bubble=false;
				break;
			case KEYS.ESC:
				this.clear();
				break;
			default:
				this.highlight_clear();				
				this.go($value(this.options.id));
				break;
		}
		return continue_bubble;
	}
	this.onFocus=function(){
        debug('Got Focus');
		this.TimeOutStop();
		this.highlight(this.last_item_highlighted);
        debug('onFocus Last item highlighted ' + this.last_item_highlighted);
		this.go($value(this.options.id));
	}
	this.onBlur=function(){
		debug("Blur start");
		var me=this;
		if(!this.options.allow_new){
			if(this.options.last_query==$value(this.options.id)	){
				this.change_display(me);
				//this.highlight(1);
			}
			this.setValue();
			this.clear();
		}else{
			me.setValue();
			me.clear();						
		}
		debug("Blur end");
		this.TimeOut=setTimeout(function () { me.clear() }, 500);
		
	}
	this.clear=function(){
		this.highlight_clear();
		this._DS_div.style.display="none";
		//this.cc.innerHTML="";
	}
	this.highlight_changed=function(key){
		if(!this.ul){return false;}
		if(this.options.last_query!=""){
			var me=this;
			this.change_display(me);			
		}
		if(this._DS_div.style.display=="none"){
			return false;
		}
		var item;
		switch(key){
			case KEYS.UP:
				item=this.item_highlighted-1;
				break;				
			case KEYS.DOWN:
				item=this.item_highlighted+1;
				break;
		}
		
		item=(item > this.ul.childNodes.length) ? this.ul.childNodes.length: item;
		if(item < 1 || isNaN(item)){
            item=1;
        }
		this.highlight(item);
	}
	this.highlight=function(item){
		if(this._DS_div.style.display=="none"){
			return false;
		}
		this.highlight_clear();
		this.item_highlighted=Number(item);		

		if (!isNull(this.ul) && this.item_highlighted > 0) {
			try{
                this.ul.childNodes[this.item_highlighted - 1].className = "highlighted";
                this._DS_div.scrollTop = this.ul.childNodes[this.item_highlighted - 1].offsetTop;
                //document.getElementById("contaner").scrollTop = document.getElementById("item-3").offsetTop;
            }catch(e){
                this.item_highlighted = 0;
            }
		}
		this.TimeOutStop();
	}
	this.highlight_clear=function(){
		if(!this.ul){
			return false;
		}
		if(this.item_highlighted > 0){
			if (!isNull(this.ul)) {			
                try{
                    this.ul.childNodes[this.item_highlighted - 1].className = "";
                }catch(e){}
			}
			this.item_highlighted = 0;
		}
	}
	this.setValue=function(){
		var item={
			val:'',
			id:'',
			info:''
		}
		debug('Item highlighted before '+this.item_highlighted);
		if((!this.options.allow_new && this.item_highlighted==0) && $(this.options.id).value != ""){
			this.highlight(1);							
		}
		debug('Item highlighted after '+this.item_highlighted);	
		if(this.item_highlighted>0){
			item=this.items[this.item_highlighted];									
		}	
		this.last_item_highlighted = this.item_highlighted
		debug('Last item highlighted '+ this.last_item_highlighted);
		if(!isNull(item)){							
			val=item.val;
			info=item.info;
			id=item.id;
			if (val!=""){
				$(this.options.id).value = val;
			}
			this.options.last_query=val;
			this.clear();
			this.options.callback(id,val,info,this.options.callback_extra);				
		}					
	}
	this.TimeOutStop=function(){
        debug('TimeOutStop');
		clearTimeout(this.TimeOut);
	}
	this.TimeOutReset=function(){
		var me=this;
		me.TimeOut=setTimeout(function () { me.clear() }, me.options.timeout);		
        debug('TimeOutReset');
	}
	
	for(option in options){
		if(typeof options[option]!='undefined'){
			this.options[option]=options[option];
		}
	}
	//Deshabilitar autocomplete
	$(options.id).setAttribute('autocomplete','off');

	this._DS_div=DOM.newObject('div');
	this.id='_objDs_'+options.id;
	this.div_id='_ds_'+options.id;
	this._DS_div.id='_ds_'+options.id;
	this._DS_div.className='autosuggest';
	
	$(options.id).parentNode.insertBefore(this._DS_div,$(options.id).nextSibling);	
	
	//HEADER
	this.hl=DOM.newObject('div');
	this.hl.className="auto_suggest_header_left";
	this._DS_div.appendChild(this.hl);
	
	this.hr=DOM.newObject('div');
	this.hr.className="auto_suggest_header_right";
	this.hl.appendChild(this.hr);
	
	this.hc=DOM.newObject('div');
	this.hc.className="auto_suggest_header_center";
	this.hr.appendChild(this.hc);	
	
	//CONTENT
	this.cl=DOM.newObject('div');
	this.cl.className="auto_suggest_center_left";
	this._DS_div.appendChild(this.cl);
	
	this.cr=DOM.newObject('div');
	this.cr.className="auto_suggest_center_right";
	this.cl.appendChild(this.cr);
	
	this.cc=DOM.newObject('div');
	this.cc.className="auto_suggest_center_center";
	this.cr.appendChild(this.cc);
	
	//BOTTOM
	this.bl=DOM.newObject('div');
	this.bl.className="auto_suggest_bottom_left";
	this._DS_div.appendChild(this.bl);
	
	this.br=DOM.newObject('div');
	this.br.className="auto_suggest_bottom_right";
	this.bl.appendChild(this.br);
	
	this.bc=DOM.newObject('div');
	this.bc.className="auto_suggest_bottom_center";
	this.br.appendChild(this.bc);
	
	
	//MANEJAR EL KEYPRESS Y KEYDOWN
	$(options.id).onkeypress=function(e){return me.onKeyPress(e);}
	$(options.id).onkeyup=function(e){return me.onKeyUp(e);}
	$(options.id).onfocus=function(e){return me.onFocus();}
	$(options.id).onclick=function(e){return me.onFocus();}

	$(options.id).onblur=function(e){return me.onBlur();}	

	$(options.id).setAttribute("autocomplete","off");

	me._DS_div.onmouseover = function(){ me.TimeOutStop() };
	me._DS_div.onmouseout = function(){  me.TimeOutReset() };

	me.change_display(me);

	me.clear();
	return this;		
}
