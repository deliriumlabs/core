/**
 * @author delirium
 */
var window_id_next=0;
var WINDOWS=[];
function Window(options){		
	this.modal=null;
	this.selected=null;
	this._moving=null;
	this.container=null;
	this.options={
		width:'400',
		height:'400',		
		max:false,
		min:false,
		close:true,
		parent:null,
		zindex:300,
		_parent_x:0,
		_parent_y:0,
		title:'DeliriumKit',
		loading_text:'Cargando',
		mvc:null,
		mvcparams:'',
		mvccallback:'',
		url:null,
		modal:false,
		html:null,
		onclose:function(){return true;},
		onload:function(){return true;}
	}
	this.show=function(){	
        this.zindex=998;
	    //DEFINIR LA VENTANA   
        this._window=DOM.newObject('div',{zIndex:this.zindex});
				
		if(this.options.modal&&!this._window.modal){			
			this._window.modal=DOM.newObject('div',
												{
													position:"fixed",													
													left:"0px",
													top:"0px",
													width:'100%',//"100%",
													height:'100%',
													zIndex:998,
													backgroundColor:"#000",
													opacity:0.5,
													filter:"alpha(opacity="+Math.round(100*0.5)+")"
												}
												);				
            this._window.modal.className="overlay";
			document.body.appendChild(this._window.modal);
			this._window.modal.innerHTML="&nbsp";	
			this.tmpkeypress=document.onkeypress;
			this.zindex++;
			//document.onkeypress=function(){return false;}
		}		
		this._parent_x=this.options.parent.style.left;
		this._parent_y=this.options.parent.style.top;				
		
		this._window.id='dw_'+this.id;	
		this._window.className='window';
		
		this._window.style.width=this.options.width+'px';
		this._window.style.height=this.options.height+'px';	
		//this._window.style.top=((getDocumentY()-this.options.height)/2)+'px';
		this._window.style.left=((getDocumentX()-this.options.width)/2)+'px';

		this._window.style.top = ((getDocumentY()-this.options.height)/2) < 0 ? "0px"  : ((getDocumentY()-this.options.height)/2)+"px" ;
		
		this._window.left=this._window.style.left;
		this._window.top=this._window.style.top;
		this._window.width=this._window.style.width;
		this._window.height=this._window.style.height;
		this._window.maximized=false;
		
		this._moving=false;	
		this.initial_x=0;
		this.initial_y=0;
		
		//LA BARRA DE TITULO
		this._title_bar=DOM.newObject('div');
		this._title_bar.className="window_title";
		this._window.appendChild(this._title_bar);
		
		this._title_bar_tr=DOM.newObject('div');
		this._title_bar_tr.className="window_tr";
		this._title_bar.appendChild(this._title_bar_tr);
		
		this._title_bar_tc=DOM.newObject('div');
		this._title_bar_tc.className="window_tc";
		this._title_bar_tr.appendChild(this._title_bar_tc);
		
		this._title_bar_header=DOM.newObject('div');
		this._title_bar_header.className="window_draggable window_header";
		this._title_bar_tc.appendChild(this._title_bar_header);		
				
		var self=this;
		//BOTON CERRAR
		if(this.options.close){
			this._close=document.createElement('div');
			this._close.className='tool tool_close';			
			this._close.onclick=function(){self.close(self._window,self._content)};			
			this._title_bar_header.appendChild(this._close);
			this._window.close=function(){self.close(self._window,self._content)};
		}
				
		//BOTON MAXIMIZAR
		if(this.options.max){
			this._maximize=document.createElement('div');
			this._maximize.className='tool_max';
			this._maximize.onclick=function(){self.maximize(self._window,self._td_contenido)};
			this._title_bar_header.appendChild(this._maximize);
		}
		//BOTON MINIMIZAR
		if(this.options.min){
			this._minimize=document.createElement('div');
			this._minimize.className='tool_min';		
			this._minimize.onclick=function(){self.minimize(self._window)};		
			this._title_bar_header.appendChild(this._minimize);
		}
		
		//BOTON IMPRIMIR
		if(this.options.min&&1>2){
			this._print=document.createElement('div');
			this._print.className='tool_print';		
			this._print.onclick=function(){self.toprint()};		
			this._title_bar_header.appendChild(this._print);
		}

		//TITULO
		this._title_bar_header_text=document.createElement('span');
		this._title_bar_header_text.className='window_header_text';
		this._title_bar_header_text.id='title_'+this.id;
		this._title_bar_header_text.innerHTML=this.options.title;	
		this._title_bar_header.appendChild(this._title_bar_header_text);
		
		//HABILITAR DRAG AND DROP
		this._title_bar_header.onmousedown=self.move_init;				
		document.onmousemove=self.move;				
		document.onmouseup=self.move_end;		
		
		//CONTENIDO 		
		var self=this;
		
		//wrapper
		this._wrap=DOM.newObject('div');
		this._wrap.className="window_wrap";
		this._window.appendChild(this._wrap);

		//wrapper izquierdo
		this._wrap_left=DOM.newObject('div');
		this._wrap_left.className="window_wrap_left";
		this._wrap.appendChild(this._wrap_left);

		//wrapper derecho
		this._wrap_right=DOM.newObject('div');
		this._wrap_right.className="window_wrap_right";
		this._wrap_left.appendChild(this._wrap_right);
		
		//wrapper CENTRO
		this._wrap_center=DOM.newObject('div');
		this._wrap_center.className="window_wrap_center";
		this._wrap_right.appendChild(this._wrap_center);
		
		//WINDOW BODY
		this._body=DOM.newObject('div');
		this._body.className="window_body";
		this._body.id='content_'+this.id;
		this._wrap_center.appendChild(this._body);
					
		this._body.innerHTML="<div align=\"center\"><p><img src=\""+delirium_skin()+"img/indicator5.gif\" ></p><p>"+this.options.loading_text+"</p></div>";		
		
		//PIE

		//PIE izquierdo
		this._footer_left=DOM.newObject('div');
		this._footer_left.className="window_footer_left";
		this._window.appendChild(this._footer_left);

		//PIE derecho
		this._footer_right=DOM.newObject('div');
		this._footer_right.className="window_footer_right";
		this._footer_left.appendChild(this._footer_right);
		
		//PIE CENTRO
		this._footer_center=DOM.newObject('div');
		this._footer_center.className="window_footer_center";
		this._footer_right.appendChild(this._footer_center);
		
		//PIE BODY
		this._footer_body=DOM.newObject('div');
		this._footer_body.className="window_footer_body";		
		this._footer_center.appendChild(this._footer_body);
		
		//WINDOW BORDES
		//TOP
		this._window_top=DOM.newObject('div');
		this._window_top.className="borde window_top";		
		this._window.appendChild(this._window_top);
		
		//BOTTOM
		this._window_bottom=DOM.newObject('div');
		this._window_bottom.className="borde window_bottom";		
		this._window.appendChild(this._window_bottom);
		
		//RIGHT
		this._window_right=DOM.newObject('div');
		this._window_right.className="borde window_right";		
		this._window.appendChild(this._window_right);

		//LEFT
		this._window_left=DOM.newObject('div');
		this._window_left.className="borde window_left";		
		this._window.appendChild(this._window_left);
		
		
		this.options.parent.appendChild(this._window);
		
		//self.center(self._window);
		//self.resize(self._window,this.options.width,this.options.height);
		document.body.focus();
		
		if (this.options.html != null) {
			this._body.innerHTML=this.options.html;
		}
        
        this.options.input_focus = function(){
            var items = $('dw_'+self.id).getElementsByTagName("input");
            for (x = 0; x < items.length;x++){
                if(items[x].type == "text"){
                    var ro = items[x].getAttribute("readonly");
                    var dis = items[x].getAttribute("disabled");
                    if( ro == null && dis== null ){
                        items[x].focus();
                        break;
                    }
                }
            }
        }
        if(this.options.mvccallback == ''){
            this.options.mvccallback = this.options.input_focus;
        }else{
            this.options.mvccallback += this.options.input_focus;
        }

		if(this.options.mvc!=null){
			this.options.mvcparams+='&window_id=dw_'+this.id;							
			mvcPost(this.options.mvc,this.options.mvcparams,'content_'+this.id,this.options.mvccallback);
		}
		this._window.refresh=function(){
			mvcPost(self.options.mvc,self.options.mvcparams,'content_'+self.id,self.options.mvccallback);
		}		
		this._window.close=function (){
			self.close(self._window);			
		}
		
		this._window.resize=function(w,h){
			self.resize(self._window,w,h);			
		}
		
		this._window.addContent=function(content){
			$('content_'+self.id).innerHTML+=content;
		}
		
		this._window.setContent=function(content){
			$('content_'+self.id).innerHTML=content;		
		}
		
		this._window.setTitle=function(title){
			$('title_'+self.id).innerHTML=title;
		}
		
		this.options.onload();
	}
	this.addContent=function(content){
		_window_=this._window;
		_window_.addContent(content);		
	}
	this.setContent=function(content){
		_window_=this._window;
		_window_.setContent(content);
	}
	this.setTitle=function(title){
		_window_=this._window;
		_window_.setTitle(title);
	}
	this.resize=function(_window,w,h){				
		var actual_top=pxToNumber(_window.style.top)||0;
		var actual_left=pxToNumber(_window.style.left)||0;
		var actual_width=pxToNumber(_window.style.width)||0;
		var actual_height=pxToNumber(_window.style.height)||0;

		var transition = new Transition(
								{
									animation:Animations.sine_curve,
									time:1000,
									callback:function(percentage) {											
										var t=(getDocumentY()-h)/2;
										var l=(getDocumentX()-w)/2;								
										
										
										if(actual_top!= t && actual_top > t && actual_top >=Math.round(percentage * t)  &&actual_top>0){											
											_window.style.top =(actual_top-(Math.round(percentage * (actual_top-t)))) + "px";		
										}										
										if(actual_left!= l && actual_left > l && actual_left >= Math.round(percentage * l)&&actual_left>0){											
											_window.style.left =(actual_left-Math.round(percentage * (actual_left-l))) + "px";		
										}																				
										if(actual_width > w && actual_width >= Math.round(percentage * w)&&actual_width>0){
											_window.style.width =(actual_width-Math.round(percentage * (actual_width-w))) + "px";		
										}										
										if(actual_height > h && actual_height >= Math.round(percentage * h)&&actual_height>0){
											_window.style.height =(actual_height-Math.round(percentage * (actual_height-h))) + "px";		
										}
																														
										if(actual_left < l && actual_left <= Math.round(percentage * l)&&actual_left>0){											
											_window.style.left =(Math.round(percentage * l)) + "px";											
										}
										
										if(actual_top < t && actual_top <= Math.round(percentage * t)&&actual_top>0){											
											_window.style.top =(Math.round(percentage * t)) + "px";		
										}
										
										if(actual_width < w && actual_width <= Math.round(percentage * w) &&actual_width>0){
											_window.style.width =Math.round(percentage * w) + "px";		
										}
										if(actual_height < h && actual_height <= Math.round(percentage * h) &&actual_height>0 ){
											_window.style.height =Math.round(percentage * h) + "px";		
										}
										
										
										if(actual_left==0){
											_window.style.left =Math.round(percentage * l) + "px";		
										}
										if(actual_top==0){
											_window.style.top =Math.round(percentage * t) + "px";		
										}
										if(actual_width==0){
											_window.style.width =Math.round(percentage * w) + "px";		
										}
										if(actual_height==0){
											_window.style.height =Math.round(percentage * h) + "px";		
										}										
									}
								}			
							);
			transition.go();			
	}
	this.center=function(_window){
		_window.style.width=(getDocumentX()/2)+'px';
		_window.style.height='0px';		
		_window.style.top=(scrollY()*.25)+'px';
		_window.style.left=(scrollX()*.25)+'px';
		
		var transition = new Transition(
								{
									animation:Animations.sine_curve,
									time:1000,
									callback:function(percentage) {						
										var h=(getDocumentY()/2);
										var w=(getDocumentX()/2);												
										_window.style.height = Math.round(percentage * h) + "px";		
									}
								}			
							);
		transition.go();				
	}
	this.close=function(_window){
		
		if(!_window){
			_window=this._window;
			}
		_window=$(_window.id);
		var _parent=_window.parentNode;				
		//debug('Cerrando window id:'+_window.id);
		
		if(_window.modal){
				try{
					//debug('Modal before remove: '+ Window.modal);
					_window.modal.parentNode.removeChild(_window.modal);	
				}catch(e){					
					debug('Window Modal remove: '+e);					
				}
				try{
				document.onkeypress=_window.tmpkeypress;
				}catch(e){}
				this.modal=null;
				//debug('Modal after remove: '+ Window.modal);
		}
		_parent.removeChild(_window);
		
		this.options.onclose();				
	}
	this.maximize=function(_window,_content){
			
		
		if(!_window.maximized){
			_window.left=_window.style.left;
			_window.top=_window.style.top;
			_window.width=_window.style.width;
			_window.height=_window.style.height;
			_content.width=_content.style.width;
			_content.height=_content.style.height;			
			
			_window.style.width='100%';
			_window.style.height=getDocumentY();//'100%';
			_content.style.width='100%';
			_content.style.height=getDocumentY();//'100%';
			_window.style.top='0px';
			_window.style.left='0px';
			_window.maximized=true;
		}else{		
			_window.style.width=_window.width;
			_window.style.height=_window.height;
			_window.style.top=_window.top;
			_window.style.left=_window.left;
			_content.style.width=_content.width;
			_content.style.height=_content.height;
			_window.maximized=false	;			
		}
				
	}
	this.minimize=function(_window){		
		alert("Aun no disponible")
	}
	this.move_init=function(e){				
		var object = getMouseObject(e);		
		Window.selected=object.parentNode.parentNode.parentNode.parentNode.parentNode;
		Window.selected.style.opacity=0.7;		
		Window.selected.style.filter="alpha(opacity="+Math.round(100*0.5)+")";
		Window.selected.initial_x = pxToNumber(Window.selected.style.left) - getMouseX(e);
		Window.selected.initial_y = pxToNumber(Window.selected.style.top)  - getMouseY(e);
		Window._moving=true;		
		return(false);
		
	}
	this.move=function(e){				
		if(Window._moving){								
			Window.selected.style.left = String(Window.selected.initial_x + getMouseX(e) ) + "px";
			Window.selected.style.top  = String(Window.selected.initial_y + getMouseY(e) ) + "px";									
			return false;												
		}
	}
	this.move_end=function(e){	
		if(!Window._moving){return false;}		
		Window.selected.style.opacity=1;
		Window.selected.style.filter="alpha(opacity="+Math.round(100*1)+")";		
		Window.selected.initial_x = 0;
		Window.selected.initial_y = 0;
				
		left=pxToNumber(Window.selected.style.left);
		top=pxToNumber(Window.selected.style.top);				
		//debug(left);
		Window.selected.style.left = left > getDocumentX() ? (getDocumentX()-50)+"px"  : left+"px" ;
		Window.selected.style.top  = top > getDocumentY() ? (getDocumentY()-50)+"px" : top+"px";
		
		left=pxToNumber(Window.selected.style.left);
		top=pxToNumber(Window.selected.style.top);
		Window.selected.style.left = left < 0 ? "0px"  : left+"px" ;
		Window.selected.style.top  = top < 0 ? "0px" : top+"px";
		
		Window.selected=null;
		Window._moving=null;
		return false;
		
	}	

	this.options.parent=document.getElementsByTagName('BODY')[0];
	for(option in options){
		if(typeof options[option]!='undefined'){
			this.options[option]=options[option];
		}
	}		
	
	this.options['onclose'] =(typeof this.options['onclose'] =="string")?eval(this.options['onclose']):this.options['onclose'];
		
	if(typeof this.options.parent=="string"){
		this.options.parent=$(this.options.parent);
	}
	var me=this;	
	this.id=window_id_next;
	window_id_next++;
	
	this.show();
	//debug('Window creada: '+this.id);	
	return this.id;
}

function _window(params){
	return new Window(params);	
	var _wnd=new Window(params);	
    //Mod DL DESIGNER
	return _wnd;
}
var WAIT_WINDOW=null;
function showWaitWindow(_title,_msg){
	WAIT_WINDOW=new Window({title:_title,loading_text:_msg,modal:true,close:false,height:80});
	//debug('Wait Window: '+WAIT_WINDOW);
	return WAIT_WINDOW;
}
function hideWaitWindow(){
	//debug(WAIT_WINDOW);
	if(WAIT_WINDOW!=null){
		WAIT_WINDOW.close();
		WAIT_WINDOW=null;	
	}
}
function addContentWaitWindow(content){
	WAIT_WINDOW.addContent("<div align=\"center\"><p>"+content+"</p></div>");
}
function setContentWaitWindow(content){
	WAIT_WINDOW.setContent("<div align=\"center\"><p>"+content+"</p></div>");
}

function _infoWindow(_title,_info,_modal,_options){
	if(typeof _modal=="undefined"){
		_modal:false;
	}
	options={
		height:80
	}
	for(option in _options){
		if(typeof options[option]!='undefined'){
			options[option]=_options[option];
		}
	}
	
	var info_window=new Window({title:_title,html:_info,modal:_modal,close:false,height:options.height});
	buttons_start='<div class="panel_buttons">';
	buttons_end='</div>';
	button_ok='<span class="button"><input type="button" value="OK" onclick="'+"$('dw_"+info_window.id+"')"+'.close();" /></span>';
	buttons=buttons_start+button_ok+buttons_end;
	info_window.addContent(buttons);
	return info_window;
}
var infoWindow = _infoWindow;
