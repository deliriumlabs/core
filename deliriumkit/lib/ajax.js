/**
 * @author delirium
 * Ultima modificacion: Wed 24 Mar 2010 08:18:13 PM CST
 */

deliriumkit.prototype.AJAX=function(options){	
	this._httpObject=null;		
	this._httpObject= new this.createHTTPObject();	
	
	for(option in options){
		if(typeof options[option]!='undefined'){
			this.options['_'+option]=options[option];
		}
	}	
	return this;	
}
deliriumkit.prototype.AJAX.prototype={		
	version:'11.03.04.1',
	onProcess:false,
	options:{
		_type:'text',
		_url:'',
		_method:'POST',
		_jsTagRegExp:"<script[\\s\\S]*?\\>[\\s\\S]*?\\<\/script>",
		_jsTagRegExpStart:"<script[\\s\\S]*?\\>",
		_jsTagRegExpEnd:"<\/script>[,]?",
		_styleTagRegExp:"<style[\\s\\S]*?\\>[\\s\\S]*?\\<\/style>",
		_styleTagRegExpStart:"<style[\\s\\S]*?\\>",
		_styleTagRegExpEnd:"<\/style>[,]?",
		_write_to:'',
		_onComplete:function(){return true;},
		_onCompleteParams:'',
		_params:null,
        _headers:[],
		_indicators:[
					delirium_skin()+'img/indicator.gif',
					delirium_skin()+'img/indicator2.gif',
					delirium_skin()+'img/indicator3.gif',
					delirium_skin()+'img/indicator4.gif',
					delirium_skin()+'img/indicator5.gif',
					delirium_skin()+'img/indicator6.gif'
					],
		_indicator:0
	},
	responseText:'',
	responseXML:'',
	setUrl:function(url){
		this.options._url=url;
	},
	setOnComplete:function(func,params){		
		this.options._onComplete=eval(func);
		if(isset(params)){
			this.options._onCompleteParams=params;
		}
	},
	setWriteTo:function(_to){
		this.options._write_to=_to;
	},
	setParams:function(params){
		this.options._params=params;
	},
	setMethod:function(method){
		this.options._method=method;
	},
	setHeaders:function(headers){
		this.options._headers = headers;
	},
	createHTTPObject:function(){
		if(!this._httpObject){
			try{
				return new ActiveXObject('Msxml2.XMLHTTP');
			}catch(e1){
				try{
					return new ActiveXObject('Microsoft.XMLHTTP');
				}catch(e2){
					try{
						return new XMLHttpRequest();
					}catch(e3){
						alert("no fue posible crear el objeto HTTPRequest");
						return null;
					}	
				}
			}
		}
	},
	send:function(){	
		if(this.onProcess){
			return false;	
		}
		if(!this._httpObject){
			this._httpObject= new this.createHTTPObject();
		}		
		var timestamp = new Date();
		var url=this.options['_url'];
  		url = url+ (url.indexOf("?") > 0 ? "&" : "?")+ "timestamp="+ timestamp.getTime()+"&window_id=_666";
						
		this._httpObject.open(this.options['_method'],url,true);
		//SI PASAMOS LOS DATOS POR POST DEBEMOS ASIGNAR EL CONTENT-TYPE
		if(this.options['_method']=="POST"){
			try{
				this._httpObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");			
			}catch(e){debug(e);}
		}		
        
		this.writeto=this.options._write_to;
		this.writeto=$(this.writeto) ? $(this.writeto):null;						
        this.writeto.innerHTML="<div style=\"width:32px;margin:0px auto;\"><img src='"+this.options._indicators[this.options._indicator]+"' border=0></div>";		
		this.onComplete=this.options._onComplete;
		this._httpObject.onreadystatechange=this.eval_state.bind(this);
		this._httpObject.send(this.options._params);
	},
	doGet:function(url){	
		if(this.onProcess){
			return false;	
		}
		this.onProcess=true;
		if(!url){
			url=this.options['_url'];
		}

		if(!this._httpObject){
			this._httpObject=this.createHTTPObject();
		}		
		var timestamp = new Date();
  		url = url+ (url.indexOf("?") > 0 ? "&" : "?")+ "timestamp="+ timestamp.getTime()+"&window_id=_666";
		this._httpObject.open('GET',url,true);			
		//this._httpObject.open('POST',url,true);			
		try{
				this._httpObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");			
			}catch(e){debug(e);}		

        var total_headers = this.options._headers.length;
        headers = this.options._headers;
        for(i = 0; i < total_headers; i++){
            header = headers[i];
			try{
				this._httpObject.setRequestHeader(header[0], header[1]);			
			}catch(e){
                debug(e);
            }
        }

		this.writeto=this.options._write_to;
		this.writeto=$(this.writeto) ? $(this.writeto):null;					
		if(this.writeto!=null){
			this.writeto.innerHTML="<div style=\"width:32px;margin:0px auto;\"><img src='"+this.options._indicators[this.options._indicator]+"' border=0></div>";		
		}
		this.onComplete=this.options._onComplete;
		this._httpObject.onreadystatechange=this.eval_state.bind(this);
		this._httpObject.send(this.options._params);
	},
	doPost:function(url){
		if(this.onProcess){
			return false;	
		}
		this.onProcess=true;
		if(!url){
			url=this.options['_url'];
		}

		if(!this._httpObject){
			this._httpObject=this.createHTTPObject();
		}		
		var timestamp = new Date();
  		url = url+ (url.indexOf("?") > 0 ? "&" : "?")+ "timestamp="+ timestamp.getTime()+"&window_id=_666";
		this._httpObject.open('POST',url,true);			
		try{
				this._httpObject.setRequestHeader("Content-Type","application/x-www-form-urlencoded");			
			}catch(e){debug(e);}		

        var total_headers = this.options._headers.length;
        headers = this.options._headers;
        for(i = 0; i < total_headers; i++){
            header = headers[i];
			try{
				this._httpObject.setRequestHeader(header[0], header[1]);			
			}catch(e){
                debug(e);
            }
        }

		this.writeto=this.options._write_to;
		this.writeto=$(this.writeto) ? $(this.writeto):null;					
		if(this.writeto!=null){
			this.writeto.innerHTML="<div style=\"width:32px;margin:0px auto;\"><img src='"+this.options._indicators[this.options._indicator]+"' border=0></div>";		
		}
		this.onComplete=this.options._onComplete;
		this._httpObject.onreadystatechange=this.eval_state.bind(this);
		this._httpObject.send(this.options._params);		
	},
	eval_state:function(){		
		var state=this._httpObject.readyState;
		if(state==4){
			var status=this._httpObject.status;
			switch(status){
				case 200:																															
					
					if(this.writeto!=null){
						this.writeto.innerHTML=this._httpObject.responseText;						
					}
					
					this.evalSTYLE(this._httpObject.responseText);												
					this.evalJS(this._httpObject.responseText);												
					this.responseText=this._httpObject.responseText.remove_tags(this.options._jsTagRegExp);
					//this.responseText=this._httpObject.responseText;

					this.onComplete();
					if(this.writeto!=null){
					this.evalForms(this._httpObject.responseText);
					}
					
					break;
				case 404:					
					alert("No se encontro el recurso solicitado");
					break;	
				default:
					break;
			}
			this.onProcess=false;
		}	
	},
	evalJS:function(js){
		window.onload=function(){return false;}		
		js=js.extract_tags(this.options._jsTagRegExp).toString();			
		if(js!=""){
			js=js.strip_tags(this.options._jsTagRegExpStart,this.options._jsTagRegExpEnd);
	
			var head = document.getElementsByTagName("head")[0];
			var script = document.createElement('script');
			
			script.id = 'interptetadedScript';
			script.type = 'text/javascript';																										
			script.text ='try{'+js+'}catch(e){debug(\'Ajax evaljs: \'+e);}';
			head.appendChild(script);
			window.onload();
		}
	},
	evalSTYLE:function(css_str){
        try{
		css_str=css_str.extract_tags(this.options._styleTagRegExp).toString();			
		if(css_str!=""){
			css_str=css_str.strip_tags(this.options._styleTagRegExpStart,this.options._styleTagRegExpEnd);
	
			var head = document.getElementsByTagName("head")[0];
			var style = document.createElement('style');
			
			style.id = 'interptetadedStyle';
            style.setAttribute('type', 'text/css');

            if(style.styleSheet){// IE
                style.styleSheet.cssText = css_str;
            } else {// w3c
                var style_src = document.createTextNode(css_str); 
                style.appendChild(style_src);
            }

			head.appendChild(style);
		}
        }catch(m){alert(m.message);}
	},
	evalForms:function(response){				
		try{			
			
			//EXPRESIONES REGULARES PARA EXTRAER LOS FORMULARIOS
			//this.formFragment= new RegExp("<form[\\s\\S]*?\\>[\\s\\S]*?\\<\/form>",'img');
			this.formFragment= new RegExp("<form[\\s\\S]*?\\>",'img');
			this.formStartFragment= new RegExp("<form[\\s\\S]*?\\>","img");
			this.formEndFragment=new RegExp("<\/form>",'img');
				
			
			var _target='';
			var _do='';
			var _transform='';
			if(response.match(this.formFragment)){	
				
				var formResponse=response.match(this.formFragment);	
				for(var i=0;i<formResponse.length;i++){
					var form_props=formResponse[i].split(" ");					
				}	
				
				for(var x=0;x<form_props.length;x++){
					var opt=form_props[x].split("=");	
					if(opt.length>1){	
						var key=trim(opt[0]);
						var value=trim(opt[1]);
						
						switch(key){
							case "id":			
								var value=value.replace( /"/g, ''  );//");									
								var form=$(value);
								
								//OBTENER EL TARGET DEL FORM (un elemento del dom ya sea un div p o alguna etiqueta con innerHTML)
								try{
									form.mvctarget=form.getAttribute('_target').replace( /"/g, ''  ) || "";
								}catch(tc_target){
									form.mvctarget='';
								}
								
								//OBTENER EL CONTROLLER/ACTION A REALIZAR
								try{
									form.mvcdo=form.getAttribute('_do').replace( /"/g, ''  );									
								}catch(trydo){
                                    debug(trydo);
									form.mvcdo='';
								}

								//Transformar el texto
								try{
									form.texttransform=form.getAttribute('_transform').replace( /"/g, ''  );									
								}catch(trytext){
                                    debug(trytext);
									form.texttransform='';
								}
								
								//OBTENER LA FUNCION A EJECUTARSE AL INICIAR DE PROCESAR EL FORMULARIO
								try{
									form.onstart=form.getAttribute('_onstart').replace( /"/g, ''  );//"); || "";
								}catch(trycb){
                                    debug(trycb);
									form.onstart="";
								}
								
								//OBTENER LA FUNCION A EJECUTARSE AL TERMINAR DE PROCESAR EL FORMULARIO
								try{
									form.mvccallback=form.getAttribute('_callback') || "";
									_afterSubmit=form.mvccallback.replace( /"/g, ''  );//");
									_afterSubmit=_afterSubmit.replace('()','');	
									_afterSubmit=_afterSubmit.replace(';','');	
								}catch(trycb){
                                    debug(trycb);
									form.mvccallback="";
								}
						
								//DETECTAR SI SE TRATA DE UN FORMULARIO PARA ENVIO DE ARCHIVOS	
								
								try{
									form.change_target=form.getAttribute('enctype')|| "";														
								}catch(xt){
                                    debug(xt);
									form.change_target=''								
								}

								if(trim(form.change_target)=="multipart/form-data"){										
									var span_iframe = document.createElement('span');
									span_iframe.innerHTML="<iframe style=\"\" src=\"about:blank\" id=\"iframe_"+form.id+"\" name=\"iframe_"+form.id+"\" onload=\"if(this.ok){var tmp=_getDocumentBody();setTimeout(function(){try{parent."+_afterSubmit+"(tmp+'');}catch(e){alert(e)};},1);}else{this.ok=true;}\" ></iframe>";
									form.appendChild(span_iframe);
									iframe=$("iframe_"+form.id);
									iframe.style.display = 'none';
									iframe.style.border="1px solid red";
									iframe.ok=false;
                                    iframe._getDocumentBody= function(){
                                        var _content = _body = null;
                                        var _html = '';
                                       
                                        //Mozilla document
                                        try{
                                            _content = this.contentDocument;
                                        }catch(e){}

                                        //IE document
                                        if(typeof(_content) == 'undefined'){
                                            try{
                                                _content = this.contentWindow.document; 
                                            }catch(e2){
                                                alert('e2 '+e2);
                                            }
                                        }
                                        //Ninguno document
                                        if(typeof(_content) == 'undefined'){
                                            //alert('sin documento');
                                            return ''; 
                                        }

                                        //Mozilla Body
                                        try{
                                            _body =  _content.body;
                                        }catch(b){}

                                        //IE body
                                        if(typeof(_body) == 'undefined'){
                                            try{
                                                _body = _content.bodyElement ;
                                            }catch(b2){}
                                        }

                                        //Ninguno body
                                        if(typeof(_body) == 'undefined'){
                                            //alert('sin body');
                                            return ''; 
                                        }

                                        _html = _body.innerHTML;
                                            
                                        return _html;
                                    }
									/*iframe._do="parent."+_afterSubmit+"()";
									iframe.onload = function () {
										if(this.ok==true){
											eval(this._do);
										}
										this.ok=true;
									};
									*/
																		
									
									form.target="iframe_"+form.id;
									form.method="POST";	
								}
																
								form.onsubmit=function(){		
									
									var elements = this.elements;
									var opts="";
									
									for(var j=0;j<elements.length;j++){		
										var validar=null;
										try{
											validar=elements[j].getAttribute('validate').split("|");
										}catch(x){debug(x);}	
																							
										if(opts!=''){
											opts+="&";
										}

										switch(elements[j].type){
											
											case "checkbox":
												if(elements[j].checked){
													opts+=elements[j].id+'='+elements[j].value;
												}
												break;
												
											case "radio":
												if(elements[j].checked){
													opts+=elements[j].id+'='+elements[j].value;
												}
												break;
												
											case "select":
												var sel=elements[j];
												if(elements[j].checked){
													opts+=sel.id+'='+sel.options[sel.selectedIndex].value;
												}
												break;
												
											default:
                                                //Transformar el texto
                                                if( this.texttransform != '' ){
                                                    switch(form.texttransform){
                                                        case "uppercase":
                                                            try{
                                                            elements[j].value = elements[j].value.toUpperCase();
                                                            }catch(eUp){}
                                                            break;

                                                        case "lowercase":
                                                            try{
                                                            elements[j].value = elements[j].value.toLowerCase();
                                                            }catch(eLw){}
                                                            break;
                                                    }
                                                }
                                                opts+=elements[j].id+'='+encode(elements[j].value);
												//opts+=elements[j].id+'='+encodeURIComponent(elements[j].value);
												break;																																	
										}
									}
									
									
									//VALIDAR EL FORMULARIO
									if(!validateForm(this.id)){
										return false;	
									}				
									try{
									eval(this.onstart);
									}catch(e_onstart){debug('ajax form onstart'+e_onstart);}
									
									//VERIFICAR SI SE TRATA DE UN FORMULARIO PARA ENVIAR ARCHIVOS
									if(trim(this.change_target)=="multipart/form-data"){										
										return true;
									}														
									
									//opts="'"+opts+"'";
									//SI SE TRATABA DE UN FORMULARIO NORMAL HACEMOS EL ENVIO POR AJAX
									try{		
										mvcPost(this.mvcdo,opts,this.mvctarget,this.mvccallback);										
									}catch(m){debug("MVCPost"+m);}
																		
									return false;
								}								
								break;
						}
					}
				}					
			}																		
			
			var inputs=document.getElementsByTagName('INPUT');								
			for(var i=0;i<inputs.length;i++) { 
				var element=inputs[i];
				 if (element.getAttribute('type') == "text"){ 
					
					//element.setAttribute('autocomplete','off'); 														
				 } 
			 }					
		}catch(e){debug(e);}
	}
	
}

var AJAXRequestCount=0;
var AJAXRequest=[];
var AJAXOnProgress=false;
function mvcPost(mvc,params,target,callback){
	
	if(typeof mvc=='undefined'){
		alert('MVC Incorrecto');
	}
	
	if(typeof target!='undefined'){
		target=target.replace(new RegExp('"','g'),'');
	}

	
	params=(typeof params=='undefined')?'':params;
	var id=new UUID();
	//AJAXRequestCount++;

	AJAXRequest['_ajax_request'+id]=new dk.AJAX({write_to:target,url:'raw.php'});
	AJAXRequest['_ajax_request'+id].setParams('do='+mvc+'&'+params);
	AJAXRequest['_ajax_request'+id].callback=callback;
	
	AJAXRequest['_ajax_request'+id].callback =(typeof callback =="string")?eval(callback):callback;
	if(typeof AJAXRequest['_ajax_request'+id].callback !='function'){
		AJAXRequest['_ajax_request'+id].callback=function(){return true;}
	}	
				
	AJAXRequest['_ajax_request'+id].setOnComplete(function(){
		
		try{
		AJAXRequest['_ajax_request'+id].responseText=(typeof AJAXRequest['_ajax_request'+id].responseText!='undefined')?AJAXRequest['_ajax_request'+id].responseText:'';		
		AJAXRequest['_ajax_request'+id].callback(AJAXRequest['_ajax_request'+id].responseText);		
		}catch(aerr){debug('Ajax Callback'+AJAXRequest+' '+aerr);}		
		AJAXRequest['_ajax_request'+id]=null;
		delete AJAXRequest['_ajax_request'+id];		
		
	})
	AJAXRequest['_ajax_request'+id].doPost();	
}

function urlPost(_url,params,target,callback, headers){
	
	if(typeof _url=='undefined'){
		alert('URL Incorrecto');
	}
	
	if(typeof target!='undefined'){
		target=target.replace(new RegExp('"','g'),'');
	}

	
	params=(typeof params=='undefined')?'':params;
	headers=(typeof headers=='undefined')? [] :headers;
	var id=new UUID();
	//AJAXRequestCount++;

	AJAXRequest['_ajax_request'+id]=new dk.AJAX({write_to:target,url:_url});
	AJAXRequest['_ajax_request'+id].setParams(params);
	AJAXRequest['_ajax_request'+id].callback=callback;
    if(headers.length > 0){
        AJAXRequest['_ajax_request'+id].setHeaders(headers);
    }
	
	AJAXRequest['_ajax_request'+id].callback =(typeof callback =="string")?eval(callback):callback;
	if(typeof AJAXRequest['_ajax_request'+id].callback !='function'){
		AJAXRequest['_ajax_request'+id].callback=function(){return true;}
	}	
				
	AJAXRequest['_ajax_request'+id].setOnComplete(function(){
		
		try{
		AJAXRequest['_ajax_request'+id].responseText=(typeof AJAXRequest['_ajax_request'+id].responseText!='undefined')?AJAXRequest['_ajax_request'+id].responseText:'';		
		AJAXRequest['_ajax_request'+id].callback(AJAXRequest['_ajax_request'+id].responseText);		
		}catch(aerr){debug('Ajax Callback'+AJAXRequest+' '+aerr);}		
		AJAXRequest['_ajax_request'+id]=null;
		delete AJAXRequest['_ajax_request'+id];		
		
	})
	AJAXRequest['_ajax_request'+id].doPost();	
}

function urlGet(_url,params,target,callback, headers){
	
	if(typeof _url=='undefined'){
		alert('URL Incorrecto');
	}
	
	if(typeof target!='undefined'){
		target=target.replace(new RegExp('"','g'),'');
	}

	
	params=(typeof params=='undefined')?'':params;
	headers=(typeof headers=='undefined')? [] :headers;
	var id=new UUID();
	//AJAXRequestCount++;

	AJAXRequest['_ajax_request'+id]=new dk.AJAX({write_to:target,url:_url});
	AJAXRequest['_ajax_request'+id].setParams(params);
	AJAXRequest['_ajax_request'+id].callback=callback;
    if(headers.length > 0){
        AJAXRequest['_ajax_request'+id].setHeaders(headers);
    }
	
	AJAXRequest['_ajax_request'+id].callback =(typeof callback =="string")?eval(callback):callback;
	if(typeof AJAXRequest['_ajax_request'+id].callback !='function'){
		AJAXRequest['_ajax_request'+id].callback=function(){return true;}
	}	
				
	AJAXRequest['_ajax_request'+id].setOnComplete(function(){
		
		try{
		AJAXRequest['_ajax_request'+id].responseText=(typeof AJAXRequest['_ajax_request'+id].responseText!='undefined')?AJAXRequest['_ajax_request'+id].responseText:'';		
		AJAXRequest['_ajax_request'+id].callback(AJAXRequest['_ajax_request'+id].responseText);		
		}catch(aerr){debug('Ajax Callback'+AJAXRequest+' '+aerr);}		
		AJAXRequest['_ajax_request'+id]=null;
		delete AJAXRequest['_ajax_request'+id];		
		
	})
	AJAXRequest['_ajax_request'+id].doGet();	
}

function traduce(texto){
    var url = 'https://www.googleapis.com/language/translate/v2';
    url += '?key=AIzaSyAcEkEV33Kms1B0OA866ae97R8Ng3IpqW8';
    url += '&source=es&target=en';
    url += '&callback=x';
    url += '&q='+texto;

    var newScript = document.createElement('script');
    newScript.type = 'text/javascript';
    newScript.src = url;

    // When we add this script to the head, the request is sent off.
    document.getElementsByTagName('head')[0].appendChild(newScript);
    this.x = function(){
        alert();
        return "x";
    
    }

}

function resultado_traduccion(response){
    alert(response.data.translations[0].translatedText);
    //return "x";
}

