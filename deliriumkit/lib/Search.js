/**
 * Instanciar delirium_search
 * @author delirium
 * @param id {this} Establecer solo la palabra this.
 * @param parametros {Array} Conjunto de parametros .
 * @constructor
 */
deliriumkit.prototype.delirium_search=function(parametros){	
        //HABILITA mostrar por default el panel de busquedas
		this._mostrar_busqueda=parametros.mostrar_busqueda ? parametros.mostrar_busqueda : false;
        //HABILITA LA DEPURACION 
		this.debug=parametros.debug ? parametros.debug : false;
		//ID DEL OBJETO
		this.id=parametros.id;		
		// TABLA O VISTA PARA LA CONSULTA
		this._tabla=(parametros.tabla) ? encode(parametros.tabla): null;
		
		//TOTAL DE REGISTROS A MOSTRARSE POR PAGINA
		this._registros=parametros.registros ? parametros.registros : 50;
		
		//HABILITA O DESABILITA LA OPCION DE ORDEN MULTIPLE
		this.orden_multiple=parametros.orden_multiple ? parametros.orden_multiple : false;  
		
		//ESPECIFICA SI SE EXPORTARA A EXCEL
		this.excel=0;
		
		//PAGINA QUE SE ESTA MOSTRANDO
		this.pagina=0;
		
		//EL CONTENIDO DESPLEGADO AL CLIENTE
		this.busquedaHTML='';
		
		//EL CONTENIDO DE LAS OPIONES DE BUSQUEDA
		this.busqueda_condicion='';
		
		this.valores='';
		
		//FILTRO DE LA BUSQUEDA
		this.filtro='';
		
		//LA DESCRIPCION DE LA BUSQUEDA
		this.lbl_consulta='';
		//PARA SABER SI SE ESTA MOSTRANDO UNA LISTA POPUP
		this.mostrando=false;
		
		//EL TITULO DE LA BUSQUEDA
		this.titulo=parametros.titulo ?parametros.titulo:'';
		if (!this._tabla){
			alert('No se ha especificado la tabla de la consulta');
			return;
		} 
		
		//EL OBJETO(DIV,TD ETC) EN EL QUE SE DESPLEGARA DELIRIUMSEARCH
		this._panel=parametros.panel;				
		
		//ARRAY PARA CONTENER LOS CAMPOS
		this._campos=new Array();
		
		//RECORRER LOS CAMPOS ESPECIFICADOS
		for(_campo in parametros.campos){			
			//AGRAGAR EL CAMPO A EL CONTENEDOR
			this._campos.push(_campo.titulo);
			//CONVERTIR EL CAMPO A UN OBJETO DE TIPO CAMPO
			this._campos[this._campos.length-1]= new this.campo(_campo,parametros.campos[_campo],this._tabla);	
			
		}	
		//VARIABLE PARA LAS OPCIONES ()MOSTRAR, MODIFICAR, ETC)
		this.opciones='';
		
		//RECORRER LAS OPCIONES ESPECIFICADAS
		for(_opcion in parametros.opciones){
			if(this.opciones!=''){
				this.opciones+=",";
			}
			//RECOGER LA OPCION EN UNA VARIABLE
			var opcion=parametros.opciones[_opcion];
			
			//DETECTAR SI LA OPCION ES UN LINK O UNA FUCION AL EJECUTARSE
			var tipo_opcion=opcion.link ? 'link' : 'onclick';
			
			//AGREGAR LA OPCION A LAS OPCIONES DE DELIRIUMSEARCH
			if (tipo_opcion=='link'){
				this.opciones+=encode(opcion.titulo)+"|"+opcion.link+"|";
			}else{
				this.opciones+=encode(opcion.titulo)+"|"+opcion.onclick+"|";	
			}
			
			//RECORRER LOS PARAMETROS DE LA OPCION
			for(_parametro in opcion.parametros){
				//DETECTAR SI SE UTILIZARA UNO DE LOS CAMPOS PARA PASARLO COMO PARAMETRO
				if(opcion.parametros[_parametro].campo){
					//ESTABLECER EL VALOR DE LA VARIABLE
					this.opciones+="campo*"+opcion.parametros[_parametro].variable+"*"+opcion.parametros[_parametro].campo+"^";
				}else if(opcion.parametros[_parametro].texto){//DETECTAR SI SE PASA SOLO TEXTO COMO PARAMETRO
						this.opciones+="texto*"+opcion.parametros[_parametro].variable+"*"+opcion.parametros[_parametro].texto+"^";					
				}
			}
			
			this.opciones+="|"+tipo_opcion;
			
			//TIPO DE CLASE CSS
			var css_class=typeof(opcion.css_class) =='undefined' ? '' : opcion.css_class;
			this.opciones+="|"+css_class;
			
			var css_class_campo=typeof(opcion.css_class_campo) =='undefined' ? '' : opcion.css_class_campo;
			this.opciones+="|"+css_class_campo;
		}	
		//this.mostrar();
		return this;
}
deliriumkit.prototype.delirium_search.prototype={
	inicializar:function(id,parametros){	
        //HABILITA mostrar por default el panel de busquedas
		this._mostrar_busqueda=parametros.mostrar_busqueda ? parametros.mostrar_busqueda : false;
		//HABILITA LA DEPURACION 
		this.debug=parametros.debug ? parametros.debug : false;
		//ID DEL OBJETO
		this.id=parametros.id;		
		// TABLA O VISTA PARA LA CONSULTA
		this._tabla=(parametros.tabla) ? parametros.tabla: null;
		
		//TOTAL DE REGISTROS A MOSTRARSE POR PAGINA
		this._registros=parametros.registros ? parametros.registros : 50;
		
		//HABILITA O DESABILITA LA OPCION DE ORDEN MULTIPLE
		this.orden_multiple=parametros.orden_multiple ? parametros.orden_multiple : false;  
		
		//ESPECIFICA SI SE EXPORTARA A EXCEL
		this.excel=0;
		
		//PAGINA QUE SE ESTA MOSTRANDO
		this.pagina=0;
		
		//EL CONTENIDO DESPLEGADO AL CLIENTE
		this.busquedaHTML='';
		
		//EL CONTENIDO DE LAS OPIONES DE BUSQUEDA
		this.busqueda_condicion='';
		
		this.valores='';
		
		//FILTRO DE LA BUSQUEDA
		this.filtro='';
		
		//LA DESCRIPCION DE LA BUSQUEDA
		this.lbl_consulta='';
		//PARA SABER SI SE ESTA MOSTRANDO UNA LISTA POPUP
		this.mostrando=false;
		
		//EL TITULO DE LA BUSQUEDA
		this.titulo=parametros.titulo ?parametros.titulo:'';
		if (!this._tabla){
			alert('No se ha especificado la tabla de la consulta');
			return;
		} 
		
		//EL OBJETO(DIV,TD ETC) EN EL QUE SE DESPLEGARA DELIRIUMSEARCH
		this._panel=parametros.panel;		
		
		//INSTANCIAR UN OBJETO XMLHTTPREQUEST
		this.HTTPObject=crearHTTPObject();
		
		//ARRAY PARA CONTENER LOS CAMPOS
		this._campos=new Array();
		
		//RECORRER LOS CAMPOS ESPECIFICADOS
		for(_campo in parametros.campos){			
			//DETECTAR SI SE HA ESTABLECIDO QUE EL CAMPO USA LISTA POPUP
			if(parametros.campos[_campo].lista_pop_up&&!this.lista_pop_up){
				//ESTABLECER QUE SE MOSTRARA UNA LISTA POPUP
				this.mostrar_pop_up=true;
				//INSTANCIAR LA LISTA POPUP
				this.lista_pop_up= new lista_pop_up(parametros.campos[_campo].lista_pop_up.url);
			}			
			//AGRAGAR EL CAMPO A EL CONTENEDOR
			this._campos.push(_campo.titulo);
			//CONVERTIR EL CAMPO A UN OBJETO DE TIPO CAMPO
			this._campos[this._campos.length-1]= new this.campo(_campo,parametros.campos[_campo],this._tabla);	
			
		}	
		//VARIABLE PARA LAS OPCIONES ()MOSTRAR, MODIFICAR, ETC)
		this.opciones='';
		
		//RECORRER LAS OPCIONES ESPECIFICADAS
		for(_opcion in parametros.opciones){
			if(this.opciones!=''){
				this.opciones+=",";
			}
			//RECOGER LA OPCION EN UNA VARIABLE
			var opcion=parametros.opciones[_opcion];
			
			//DETECTAR SI LA OPCION ES UN LINK O UNA FUCION AL EJECUTARSE
			var tipo_opcion=opcion.link ? 'link' : 'onclick';
			
			//AGREGAR LA OPCION A LAS OPCIONES DE DELIRIUMSEARCH
			if (tipo_opcion=='link'){
				this.opciones+=encode(opcion.titulo)+"|"+opcion.link+"|";
			}else{
				this.opciones+=encode(opcion.titulo)+"|"+opcion.onclick+"|";	
			}
			
			//RECORRER LOS PARAMETROS DE LA OPCION
			for(_parametro in opcion.parametros){
				//DETECTAR SI SE UTILIZARA UNO DE LOS CAMPOS PARA PASARLO COMO PARAMETRO
				if(opcion.parametros[_parametro].campo){
					//ESTABLECER EL VALOR DE LA VARIABLE
					this.opciones+="campo*"+opcion.parametros[_parametro].variable+"*"+opcion.parametros[_parametro].campo+"^";
				}else if(opcion.parametros[_parametro].texto){//DETECTAR SI SE PASA SOLO TEXTO COMO PARAMETRO
						this.opciones+="texto*"+opcion.parametros[_parametro].variable+"*"+opcion.parametros[_parametro].texto+"^";					
				}
			}
			
			this.opciones+="|"+tipo_opcion;
			
		}					
	},
	mostrar:function(where,printer){
		if (this._panel){
			//document.getElementById(this._panel).innerHTML="<p><img src=\"imagenes/ajax_loading2.gif\" /></p>";
		}
		var campos ='campos=';
		var filtro = typeof (where) == 'undefined' ? '' : where;	
		filtro = decode(filtro);
		this.tmpfiltro = filtro;
		
		this.busquedaHTML='';
		this.busquedaHTML+="<tr>";
		
		this.busquedaHTML+="<td align='center' >";
		this.busquedaHTML+="<div id='div_"+this.id+"_y_o'>&nbsp;</div>";
		this.busquedaHTML+="</td>"
		
		this.busquedaHTML+="<td align='center' >";
		this.busquedaHTML+="<select name='"+this.id+"_"+"campo' id='"+this.id+"_"+"campo'  onchange=\"eval('"+this.id+"').pop_up_mostrar(this.value);\"  >";	
		this.busquedaHTML+="<option value='*'>%BUSQUEDA_RAPIDA%</option>";
		for(var x=0;x<this._campos.length;x++){	
			var _campo=this._campos[x];
			if(x>0){
				campos+=",";
			}		
			campos+=_campo._titulo+"|"+_campo._campo+"|"+_campo._orden+"|"+_campo._tipo+"|";	
			if(_campo.mostrar == true){
				campos+="1|";
			}else{
				campos+="0|";
			}			
			campos+=_campo._orden_abs+"|"+_campo._width+"|";
			campos+=_campo._alinear+"|";
			if(_campo._para_busqueda == true){				
				this.busquedaHTML+="<option value='"+_campo._campo+"'>"+decode(_campo._titulo)+"</option>";
			}
		}
		this.busquedaHTML+="</select>";	
		this.busquedaHTML+="</td>";
		
		//CONDICIONES DE BUSQUEDA
		this.busqueda_condicion="<select name='"+this.id+"_"+"condicion' id='"+this.id+"_"+"condicion'  onchange=\"eval('"+this.id+"').set_valores(this.value,'"+_campo._tipo+"');\"  >";
		this.busqueda_condicion+="<option value='contenga'>%CUALQUIER_CAMPO_CONTENGA%</option></select>";
		this.busquedaHTML+="<td align='center' id='div_"+this.id+"_"+"condicion'  >&nbsp;"+this.busqueda_condicion;
		this.busquedaHTML+="</td>";				
		
		//Valor a Buscar
		this.valores="<input type='text' validate='noempty' name='"+this.id+"_valor' id='"+this.id+"_valor'/>";		
		this.busquedaHTML+="<td id='div_"+this.id+"_"+"valores'  ><div id='delirium_search_query'>";
		this.busquedaHTML+=this.valores;
		this.busquedaHTML+="</div></td>";									
		
		this.busquedaHTML+="<tr>";
		this.busquedaHTML+="<td colspan='4' align='center' >";
		this.busquedaHTML+="<span class='button'><input type='button' name='agregar_a_busqueda' id='agregar_a_busqueda' value='%AGREGAR_A_LA_BUSQUEDA%' onclick=\"eval('"+this.id+"').agregar_a_busqueda(eval('"+this.id+"').id);\" /></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='limpiar_busqueda' id='limpiar_busqueda' value='%LIMPIAR_LA_BUSQUEDA%' onclick=\"eval('"+this.id+"').limpiar_busqueda(eval('"+this.id+"').id);\" /></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='buscar' id='buscar' value='%BUSCAR%' onclick=\"eval('"+this.id+"').pagina=0;eval('"+this.id+"').mostrar_resultado(eval('"+this.id+"').filtro);\" /></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='export_excel' id='export_excel' value='%EXPORTAR_RESULTADOS%' onclick=\"eval('"+this.id+"').pagina=0;eval('"+this.id+"').exportar_a_excel();\" ></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='pintThis' id='pintThis' value='%IMPRIMIR%' onclick=\"eval('"+this.id+"').pagina=0;eval('"+this.id+"').toPrint();\" ></span>";
		this.busquedaHTML+="</td>";
				
		this.busquedaHTML+="<tr>";
		this.busquedaHTML+="<td colspan='4' align='center'>";
		this.busquedaHTML+="<div id='div_"+this.id+"_consulta' align='center'>"+this.lbl_consulta+"</div>";
		this.busquedaHTML+="</td>";
		this.busquedaHTML+="</tr>";

		this.busquedaHTML="<table border=0 width='100%' cellpadding=0 cellspacing=0 align='center'>"+this.busquedaHTML+"</table>"
				
		
		/*var _me=this;		
		var panel=this._panel;
		var _params=campos+'&tabla='+this._tabla+'&registros='+this._registros+'&where='+filtro+'&delirium_request='+this.id+'&pagina='+this.pagina+'&opciones='+this.opciones+"&excel="+this.excel+"&titulo="+escape(this.titulo)+'&base='+escape(this.busquedaHTML);
		if(this.excel==0){
			//window.location=location+'&'+_params;
			mvcPost('DeliriumSearch',_params,panel);			
		}else{ 			
			//window.open('js/ajax/root.asp?'+campos+'&tabla='+this._tabla+'&registros='+this._registros+'&where='+filtro+'&delirium_request='+this.id+'&pagina='+this.pagina+'&opciones='+this.opciones+"&excel=1&titulo="+this.titulo);
		}*/
		
		var _me=this;		
		var panel=this._panel;
		var _params=campos+'&tabla='+this._tabla+'&registros='+this._registros+'&where='+filtro+'&delirium_request='+this.id+'&pagina='+this.pagina+'&opciones='+this.opciones+"&excel="+this.excel+"&titulo="+encode(this.titulo)+'&base='+encode(this.busquedaHTML)+'&debug='+this.debug;
		
		if(this.excel==0){
			if (typeof(printer)!='undefined') {				
				mvcPost('DeliriumSearch', _params+'&excel=1', '',_me.Print);
			}
			else {
				mvcPost('DeliriumSearch', _params, panel);
			}			
		}else{ 			
            var form = $('form_search_excel');
            if(form  == null){
                var form = DOM.newObject('form');
                form.id = 'form_search_excel';
                form.setAttribute("_do", 'deliriumsearch');
                form.target = '_blank';
                form.method = 'POST';
                document.body.appendChild(form);
                $hide('form_search_excel');
            }

            form.innerHTML = '';

            //Tabla
            var tabla = DOM.newObject('textarea');
            tabla.id = 'tabla'
            tabla.name = 'tabla'
            tabla.value = decode(this._tabla).replace(/\+/g,' ');
            form.appendChild(tabla);
            
            //Registros
            var registros = DOM.newObject('INPUT');
            registros.type ='text';
            registros.id = 'registros'
            registros.name = 'registros'
            registros.value = this._registros;
            form.appendChild(registros);

            //where
            var where = DOM.newObject('textarea');
            where.id = 'where'
            where.name = 'where'
            //where.value = this.filtro;
            where.value = decode(this.filtro);
            if(where.value == ''){
                where.value = decode(filtro);
            }
            form.appendChild(where);

            //delirium_request
            var delirium_request = DOM.newObject('INPUT');
            delirium_request.type ='text';
            delirium_request.id = 'delirium_request'
            delirium_request.name = 'delirium_request'
            delirium_request.value = this.id;
            form.appendChild(delirium_request);

            //pagina
            var pagina = DOM.newObject('INPUT');
            pagina.type = 'text'
            pagina.id = 'pagina'
            pagina.name = 'pagina'
            pagina.value = this.pagina;
            form.appendChild(pagina);

            //opciones
            var opts = DOM.newObject('textarea');
            opts.id = 'opciones'
            opts.name = 'opciones'
            opts.value = this.opciones;
            form.appendChild(opts);

            //Excel
            var excel = DOM.newObject('INPUT');
            excel.type = 'text'
            excel.id = 'excel'
            excel.name = 'excel'
            excel.value = this.excel;
            form.appendChild(excel);

            //Titulo
            var titulo = DOM.newObject('INPUT');
            titulo.type ='text';
            titulo.id = 'titulo'
            titulo.name = 'titulo'
            titulo.value = decode(this.titulo).replace(/\+/g,' ');
            form.appendChild(titulo);

            //base
            var base = DOM.newObject('textarea');
            base.id = 'base'
            base.name = 'base'
            base.value = decode(this.busquedaHTML).replace(/\+/g,' ');
            form.appendChild(base);

            //campos
            var _campos = DOM.newObject('textarea');
            _campos.id = 'campos'
            _campos.name = 'campos'
            _campos.value = decode(campos.replace('campos=','')).replace(/\+/g,' ');
            form.appendChild(_campos);

            //debug
            var debug = DOM.newObject('INPUT');
            debug.id = 'debug'
            debug.name = 'debug'
            debug.value = this.debug;
            form.appendChild(debug);

            send_form('form_search_excel');
            
			//window.open('helper.php?do=deliriumsearch&'+_params+'&excel=1');
		}

		this.filtro='';
		this.lbl_consulta='';	
	},
	Print:function(response){
		toPrintHTML(response);
	},
	show:function(where){
		if (isNull($(this._panel))){
			return false;
			//document.getElementById(this._panel).innerHTML="<p><img src=\"imagenes/ajax_loading2.gif\" /></p>";
		}
		var campos ='campos=';
		var filtro = typeof (where) == 'undefined' ? '' : where;	
		this.tmpfiltro = filtro;
		
		this.busquedaHTML='';
		this.busquedaHTML+="<tr>";
		
		this.busquedaHTML+="<td align='center' >";
		this.busquedaHTML+="<div id='div_"+this.id+"_y_o'>&nbsp;</div>";
		this.busquedaHTML+="</td>"
		
		this.busquedaHTML+="<td align='center' >";
		this.busquedaHTML+="<select name='"+this.id+"_"+"campo' id='"+this.id+"_"+"campo'  onchange=\"eval('"+this.id+"').pop_up_mostrar(this.value);\"  >";	
		this.busquedaHTML+="<option value='*'>%BUSQUEDA_RAPIDA%</option>";
		for(var x=0;x<this._campos.length;x++){	
			var _campo=this._campos[x];
			if(x>0){
				campos+=",";
			}		
			campos+=_campo._titulo+"|"+_campo._campo+"|"+_campo._orden+"|"+_campo._tipo+"|";	
			if(_campo.mostrar == true){
				campos+="1|";
			}else{
				campos+="0|";
			}			
			campos+=_campo._orden_abs+"|"+_campo._width+"|";
			campos+=_campo._alinear+"|";
			if(_campo._para_busqueda == true){				
				this.busquedaHTML+="<option value='"+_campo._campo+"'>"+decode(_campo._titulo)+"</option>";
			}
		}
		this.busquedaHTML+="</select>";	
		this.busquedaHTML+="</td>";
		
		//CONDICIONES DE BUSQUEDA
		this.busqueda_condicion="<select name='"+this.id+"_"+"condicion' id='"+this.id+"_"+"condicion'  onchange=\"eval('"+this.id+"').set_valores(this.value,'"+_campo._tipo+"');\"  >";
		this.busqueda_condicion+="<option value='contenga'>%CUALQUIER_CAMPO_CONTENGA%</option></select>";
		this.busquedaHTML+="<td align='center' id='div_"+this.id+"_"+"condicion'  >&nbsp;"+this.busqueda_condicion;
		this.busquedaHTML+="</td>";				
		
		//Valor a Buscar
		this.valores="<input type='text' validate='noempty'name='"+this.id+"_valor' id='"+this.id+"_valor'/>";		
		this.busquedaHTML+="<td id='div_"+this.id+"_"+"valores'  ><div id='delirium_search_query'>";
		this.busquedaHTML+=this.valores;
		this.busquedaHTML+="</div></td>";									
		
		this.busquedaHTML+="<tr>";
		this.busquedaHTML+="<td colspan='4' align='center' >";
		this.busquedaHTML+="<span class='button'><input type='button' name='agregar_a_busqueda' id='agregar_a_busqueda' value='%AGREGAR_A_LA_BUSQUEDA%' onclick=\"eval('"+this.id+"').agregar_a_busqueda(eval('"+this.id+"').id);\" /></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='limpiar_busqueda' id='limpiar_busqueda' value='%LIMPIAR_LA_BUSQUEDA%' onclick=\"eval('"+this.id+"').limpiar_busqueda(eval('"+this.id+"').id);\" /></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='buscar' id='buscar' value='%BUSCAR%' onclick=\"eval('"+this.id+"').pagina=0;eval('"+this.id+"').mostrar_resultado(eval('"+this.id+"').filtro);\" /></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='export_excel' id='export_excel' value='%EXPORTAR_RESULTADOS%' onclick=\"eval('"+this.id+"').pagina=0;eval('"+this.id+"').exportar_a_excel();\" ></span>";
		this.busquedaHTML+="<span class='button'><input type='button' name='toPrint' id='toPrint' value='%IMPRIMIR%' onclick=\"eval('"+this.id+"').pagina=0;eval('"+this.id+"').toPrint();\" ></span>";
		this.busquedaHTML+="</td>";
		

		this.busquedaHTML+="</tr>";
				
		this.busquedaHTML+="<tr>";
		this.busquedaHTML+="<td colspan='4' align='center'>";
		this.busquedaHTML+="<div id='div_"+this.id+"_consulta' align='center'>"+decode(this.lbl_consulta)+"</div>";
		this.busquedaHTML+="</td>";
		this.busquedaHTML+="</tr>";

		this.busquedaHTML="<table border=0 width='100%' cellpadding=0 cellspacing=0 align='center'>"+this.busquedaHTML+"</table>"
				
		
		var _me=this;		
		var panel=this._panel;
		var _params=campos+'&tabla='+this._tabla+'&registros='+this._registros+'&where='+decode(filtro)+'&delirium_request='+this.id+'&pagina='+this.pagina+'&opciones='+this.opciones+"&excel="+this.excel+"&titulo="+encode(this.titulo)+'&base='+encode(this.busquedaHTML)+'&debug='+this.debug;
		if(this.excel==0){
			mvcPost('DeliriumSearch',_params,panel);			
		}else{ 			
			window.open('helper.php?'+decode(_params)+'&excel=1');
		}

		this.filtro='';
		this.lbl_consulta='';	
		
	},
	
	consultar_resultado:function(panel,ajax,busquedaHTML){	
		var tabla_titulo="<table border=0 width='100%' >";
		tabla_titulo+="<tr><td colspan=6 align='left' id='"+this.id+"_mostrar_ocultar'>"
		tabla_titulo+="<a href='javascript:void(0);' onclick=\"eval('"+this.id+"').mostrar_ocultar(this);\" >%MOSTRAR_OCULTAR_CUADRO_BUSQUEDA%</a>"
		tabla_titulo+="</td></tr>";		
		tabla_titulo+="</table><br>";
		document.getElementById(panel).innerHTML=tabla_titulo;
		var display=this.mostrando ? '' : '';		
		var div="<div id='div_"+this.id+"_busqueda_contenedor' style=\"width:100%;display:'"+display+"';border-bottom:outset;border-right:outset; color:#000000\">";

		div+=busquedaHTML;
		div+="</div><br>";
		document.getElementById(panel).innerHTML+=div
		document.getElementById(panel).innerHTML+=ajax.responseText;				
		this.mostrar_ocultar();
		
	},
	mostrar_resultado:function(id){		
		this.excel= 0;
		var where='';						
		this.mostrar(id);
		this.filtro='';
		this.lbl_consulta='';
		//this.pagina=0;
	},
	refresh:function(){		
		this.mostrar(this.tmpfiltro);
	},
	exportar_a_excel:function(){
		this.excel= 1;
		this.mostrar(this.tmpfiltro);
	},
	toPrint:function(){		
		this.mostrar(this.tmpfiltro,true);
	},
	ordenar:function(campo){
		for(var x=0;x<this._campos.length;x++){	
			var _campo=this._campos[x];			
			if(_campo._campo == campo){				
				if(_campo._orden=='asc'||_campo._orden=='' ){
					_campo._orden='desc';
				}else{
					_campo._orden='asc';
				}				
			}else{				
				if (!this.orden_multiple){					
					_campo._orden='';
				}
			}			
		}
		this.excel= 0;
		this.mostrar(this.tmpfiltro);
	},
	agregar_a_busqueda:function(id){//CONCATENA LOS FILTROS PARA CREAR EL WHERE
        if(!validateDiv('div_'+this.id+'_busqueda_contenedor')){
            return false;
        }
		var campo=document.getElementById(this.id+"_campo").value;
		var condicion=document.getElementById(this.id+"_"+"condicion").value;
		var valor='';
		var valor_inicial='';
		var valor_final='';
		var y_o=document.getElementById(this.id+"_select_y_o") ? document.getElementById(this.id+"_select_y_o").value : '';
		var tipo='';
		
		if(campo=="*"){
			valor=encode(document.getElementById(this.id+"_valor").value);
			for(var x=0;x<this._campos.length;x++){	
				var _campo=this._campos[x];			

				tipo=_campo._tipo;

				if (trim(valor)!=""){
					
					if(this.filtro!=''){				
						this.filtro+='OR'
						this.filtro+=','
					}
					this.filtro+=_campo._campo+"|contenga|"+valor+"|"+tipo+"|";
				}
			}
			document.getElementById('div_'+id+'_consulta').innerHTML="%CUALQUIER_CAMPO_CONTENGA% "+decode(valor)+"<br>";
		}else{				
			for(var x=0;x<this._campos.length;x++){	
				var _campo=this._campos[x];			
				if(_campo._campo == campo){				
					tipo=_campo._tipo;
					titulo=_campo._titulo;
				}			
			}
			//SI EL TIPO ES FECHA
			if (tipo=='fecha'){	
				//SI TENEMOS VALORES OBTENER INICIAL Y FINAL
				if(document.getElementById(this.id+"_valor_final")){				
					valor_inicial=document.getElementById(this.id+"_valor_inicial").value;
					valor_final=document.getElementById(this.id+"_valor_final").value;
					valor=valor_inicial+"*"+valor_final;
				}else{
					//SI SOLO ES UN CAMPO DE FECHA ASIGNARLO AL VALOR DE LA BUSQUEDA SIMPLE
					valor=document.getElementById(this.id+"_valor_inicial").value;
				}
			}else{
				//OBTENER EL VALOR DE LA CONSULTA
				valor=encode(document.getElementById(this.id+"_valor").value);
			}
			
			if (trim(valor)!=""){
				this.filtro+=y_o
				if(this.filtro!=''){				
					this.filtro+=','
				}
				this.filtro+=campo+"|"+condicion+"|"+valor+"|"+tipo+"|";
			}
			switch(y_o){
				case 'AND':
					this.lbl_consulta+=' %Y% ';
					break;
				case 'OR':
					this.lbl_consulta+=' %O% ';
					break;
				default:
					this.lbl_consulta+=' %EL_DATO% ';
					break;
			}
			//valor=decode(valor);
            valor=decode(valor);
			//AGREGAR EL TITULO AL LABEL
			this.lbl_consulta+=decode(titulo);
			switch(condicion){
				case 'igual':
					this.lbl_consulta+=' %IGUAL_A% ';
					this.lbl_consulta+=valor;
					break;
				case 'contenga':
					this.lbl_consulta+=' %CONTENGA% ';
					this.lbl_consulta+=valor;
					break;
				case 'entre':
					this.lbl_consulta+=' %ENTRE% '
					this.lbl_consulta+=valor_inicial + ' y ' + valor_final;
					break;
				case 'mayor':
					this.lbl_consulta+=' %MAYOR_A% ';
					this.lbl_consulta+=valor;
					break;
				case 'mayorigual':
					this.lbl_consulta+=' %MAYOR_IGUAL% ';
					this.lbl_consulta+=valor;
					break;
				case 'menor':
					this.lbl_consulta+=' %MENOR_A%  ';
					this.lbl_consulta+=valor;
					break;
				case 'menorigual':
					this.lbl_consulta+=' %MENOR_IGUAL% ';
					this.lbl_consulta+=valor;
					break;
				case 'inicie':
					this.lbl_consulta+=' %INICIA_CON% ';
					this.lbl_consulta+=valor;
					break;
				case 'termine':
					this.lbl_consulta+=' %TERMINE_CON% ';
					this.lbl_consulta+=valor;
					break;
			}
					
			document.getElementById('div_'+id+'_consulta').innerHTML=this.lbl_consulta+"<br>";
			
			if(document.getElementById('div_'+id+'_consulta').innerHTML!=""){
				document.getElementById('div_'+id+'_y_o').innerHTML="<select name='"+id+"_select_y_o' id='"+id+"_select_y_o'><option value='AND'>%Y%</option><option value='OR'>%O%</option></select>";			
			}
		}
	},
	limpiar_busqueda:function(id){//LIMPIA LA CADENA QUE MANEJA LA BUSQUEDA
		document.getElementById('div_'+id+'_consulta').innerHTML="";
		document.getElementById('div_'+id+'_y_o').innerHTML="";
		this.filtro='';
		this.lbl_consulta='';
	},
	pop_up_mostrar:function(campo){
		document.getElementById('div_'+this.id+'_'+'valores').innerHTML='';
		document.getElementById('div_'+this.id+'_'+'condicion').innerHTML='';
		/*if(campo=='0'){
			this.mostrar_pop_up=false;			
			return false;
		}*/
		this.busqueda_condicion="";
		this.mostrar_pop_up=false;
		for(var x=0;x<this._campos.length;x++){	
			var _campo=this._campos[x];			
			if(_campo._campo == campo){				
				if(_campo._lista_pop_up){
					this.lista_pop_up.url=_campo._lista_pop_up_url;
					this.mostrar_pop_up=true;
				}
				this.busqueda_condicion="<select name='"+this.id+"_"+"condicion' id='"+this.id+"_"+"condicion'  onchange=\"eval('"+this.id+"').set_valores(this.value,'"+_campo._tipo+"');\"  >";
				this.busqueda_condicion+="<option value='0'>%SELECCIONE%</option>";

				switch(_campo._tipo){				
					case "texto":								
						this.busqueda_condicion+="<option value='igual'>%IGUAL_A%</option>";				
						this.busqueda_condicion+="<option value='contenga'>%CONTENGA%</option>";
						this.busqueda_condicion+="<option value='inicie'>%INICIE_CON%</option>";
						this.busqueda_condicion+="<option value='termine'>%TERMINE_CON%</option>";
						break;
					case "numero":
					case "moneda":
						this.busqueda_condicion+="<option value='igual'>%IGUAL_A%</option>";
						this.busqueda_condicion+="<option value='mayor'>%MAYOR_A%</option>";
						this.busqueda_condicion+="<option value='mayorigual'>%MAYOR_IGUAL%</option>";
						this.busqueda_condicion+="<option value='menorigual'>%MENOR_A%</option>";
						this.busqueda_condicion+="<option value='menorigual'>%MENOR_IGUAL%</option>";
						break;
					case "fecha":
						this.busqueda_condicion+="<option value='entre'>%ENTRE_LAS_FECHAS%</option>";
						this.busqueda_condicion+="<option value='igual'>%IGUAL_A%</option>";
						this.busqueda_condicion+="<option value='mayor'>%MAYOR_A%</option>";
						this.busqueda_condicion+="<option value='mayorigual'>%MAYOR_IGUAL%</option>";
						this.busqueda_condicion+="<option value='menorigual'>%MENOR_A%</option>";
						this.busqueda_condicion+="<option value='menorigual'>%MENOR_IGUAL%</option>";
						break;
					default:
						this.busqueda_condicion+="<option value='contenga'>%CUALQUIER_CAMPO_CONTENGA%</option></select>";
						break;
				}
				this.busqueda_condicion+="</select>";
				document.getElementById('div_'+this.id+'_'+'condicion').innerHTML=this.busqueda_condicion;
				break;
			}						
		}
		if(trim(this.busqueda_condicion)==""){
			this.busqueda_condicion="<select name='"+this.id+"_"+"condicion' id='"+this.id+"_"+"condicion'  onchange=\"eval('"+this.id+"').set_valores(this.value,'"+_campo._tipo+"');\"  >";
			this.busqueda_condicion+="<option value='contenga'>%CUALQUIER_CAMPO_CONTENGA%</option></select>";
			document.getElementById('div_'+this.id+'_'+'condicion').innerHTML=this.busqueda_condicion;
			this.valores="<div id='delirium_search_query'><input type='text' validate='noempty'name='"+this.id+"_valor' id='"+this.id+"_valor'   /></div>";	
			document.getElementById('div_'+this.id+'_'+'valores').innerHTML=this.valores;
		};
	},
	set_valores:function(condicion,tipo){
		if (condicion=='0'){
			document.getElementById('div_'+this.id+'_'+'valores').innerHTML='';
			return false;
		}
		switch(tipo){
			case 'fecha':
				this.valores="<input type='text' validate='noempty|fecha' name='"+this.id+"_valor_inicial' id='"+this.id+"_valor_inicial' class='MiTxtBox'  />";
				this.valores+="<span ><img src='jscalendar/img.gif' border='0' id='f_trigger_a' style='cursor: pointer; border: 0px solid red;' title='%FECHA_INICIAL%'/></span>";
				switch(condicion){
					case 'entre':													
						this.valores+=" %Y% ";
						
						this.valores+="<input type='text' validate='noempty|fecha' name='"+this.id+"_valor_final' id='"+this.id+"_valor_final' class='MiTxtBox'  />";
						this.valores+="<span ><img src='jscalendar/img.gif' border='0' id='f_trigger_b' style='cursor: pointer; border: 0px solid red;' title='%FECHA_FINAL%'/></span>";
						break;					
				}

				document.getElementById('div_'+this.id+'_'+'valores').innerHTML=this.valores;
				Calendar.setup({
					inputField     :    this.id+"_valor_inicial",     // id of the input field
					ifFormat       :    "%d-%m-%Y",      // format of the input field
					button         :    "f_trigger_a",  // trigger for the calendar (button ID)
					align          :    "Tl",           // alignment (defaults to "Bl")
					singleClick    :    true
				});
				if(condicion=='entre'){
					Calendar.setup({
						inputField     :    this.id+"_valor_final",     // id of the input field
						ifFormat       :    "%d-%m-%Y",      // format of the input field
						button         :    "f_trigger_b",  // trigger for the calendar (button ID)
						align          :    "Tl",           // alignment (defaults to "Bl")
						singleClick    :    true
					});
				}
				break;
			default:
				this.valores="<div id='delirium_search_query'><input type='text' validate='noempty' name='"+this.id+"_valor' id='"+this.id+"_valor'   /></div>";
				document.getElementById('div_'+this.id+'_'+'valores').innerHTML=this.valores;
				break;
		}

	},
	pop_up:function(pagina,e){
		if(!this.mostrar_pop_up){return;}
		if(!e) e=window.event;
		this.lista_pop_up.seleccion(e);
		this.lista_pop_up.consultar(pagina,this.id+'_valor',this.id+'_valor');				
	},	
	asignar:function (valor){
		this.lista_pop_up.asignar_valor(valor,this.id+'_valor');
	},
	mostrar_pagina:function(pagina){
		this.pagina=pagina;
		this.mostrar(this.tmpfiltro);	
	},
	mostrar_ocultar:function(activador){				
		if(typeof activador == 'undefined'){
			
		}else{			
			this.mostrando=this.mostrando ? false:true;					
		}
		var display=!this.mostrando ? 'none' : '';
		document.getElementById('div_'+this.id+'_busqueda_contenedor').style.display=display;
        if(this._mostrar_busqueda){
            $show('div_'+this.id+'_busqueda_contenedor');
        }
		
	},
	campo:function(campo,_campo,tabla){
		this._tabla = tabla;
		this._campo = campo;
			
		this._width=(_campo.width)?_campo.width:'';
		this._tipo = _campo.tipo;
		this._titulo = encode(_campo.titulo);
		this._orden = _campo.orden ? _campo.orden: '' ;
		this._orden_abs = _campo.orden_abs ? _campo.orden_abs: '' ;
		this.mostrar = _campo.mostrar ? true : false ;		
		this._para_busqueda = _campo.para_busqueda ? true : false ;		
		this._lista_pop_up=false;
		if(_campo.lista_pop_up){				
			this._lista_pop_up_url=_campo.lista_pop_up['url'];
			this._lista_pop_up=true;
		}	
		this._alinear = typeof(_campo.alinear) != 'undefined' ? _campo.alinear : '' ;		
	},
	opcion:function(){
		
	}
}
