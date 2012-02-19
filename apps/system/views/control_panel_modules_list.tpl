<script>
	var modulo=null;
	var total_menus=0;
	var menu_actual=0;
	var pasos_instalacion=0;
	var paso_actual=1;
	var fase="";
	function instalar_modulo(response){
		response=trim(response);
		modulo=null;
		pasos_instalacion=0;
		paso_actual=1;
		total_menus=0;
		menu_actual=0;
		fase="";

		modulo=parseJson(response);
		modulo=modulo[0];
		total_menus=modulo.menus.length;
		pasos_instalacion=modulo.pasos_instalacion;
		menu_actual=0;

		fase="menu";
		showWaitWindow("Instalando :"+modulo.titulo );

		instalar();
	}

	function instalar(){
			switch(fase){
				case "menu":
					if(menu_actual<total_menus){
						setContentWaitWindow("Instalando menu:"+modulo.menus[menu_actual].titulo);
						mvcPost("System::admin_modulo_instalar_menu",'uuid='+modulo.uuid+'&menu='+menu_actual,'','incrementar_menu');
					}else{
						fase="tablas";
						instalar();
					}
					break;
				case "tablas":
					if(paso_actual<=pasos_instalacion){
						setContentWaitWindow(modulo["paso_"+paso_actual].label);
						mvcPost("System::admin_modulo_instalar_paso",'uuid='+modulo.uuid+'&paso='+paso_actual,'','incrementar_paso_instalacion');
					}else{
						fase="final";
						instalar();
					}
					break;
				case "final":
					fase="null";
                    reset_install_variables();
					break;
				default:
					mvcPost('System::admin_modulos_instalar','','control_panel_work','reset_install_variables');
					break;
			}
	}

	function incrementar_menu(){
		menu_actual++;
		mvcPost("core_menu","","menu");
		instalar();
	}

	function incrementar_paso_instalacion(response){
        var response = response.split("|");
        if(response[0]!=""){
            hideWaitWindow();
            _infoWindow('%_ERROR_%',response[0],true,{height:300});
        }else{
            paso_actual++;
            instalar();
        }
	}

	function reset_install_variables(){
		modulo=null;
		pasos_instalacion=0;
		paso_actual=1;
		total_menus=0;
		menu_actual=0;
		fase="";
        hideWaitWindow();
        if(lista_modulos.length > 0){
            debug("cantidad de modulos seleccionados = "+ lista_modulos.length);
            debug("instalando "+lista_modulos[0].value);
            mvcPost('System::admin_modulos_info','uuid='+lista_modulos[0].value,'','instalar_modulo');
            lista_modulos.splice(0,1);
        }else{
            mvcPost('System::admin_modulos','','control_panel_work');
        }
	}

    var lista_modulos = [];
    function instalar_modulos_lista(){
        lista_modulos = $TagNames('input',$('lista_modulos')).filter(
                function(x){
                    if(x.type == "checkbox"){ 
                        if(x.checked){
                            return true;                    
                        }
                    }
                });
        debug("cantidad de modulos seleccionados = "+ lista_modulos.length);
        if(lista_modulos.length > 0){
            debug("instalando "+lista_modulos[0].value);
            mvcPost('System::admin_modulos_info','uuid='+lista_modulos[0].value,'','instalar_modulo');
            lista_modulos.splice(0,1);
        }
    }

    function marcar_desmarcar(){
        if( $j('#todos_ninguno').attr('checked') == true ){
            $j('#lista_modulos input:checkbox').attr('checked','true');
        }else{
            $j('#lista_modulos input:checkbox').attr('checked','');
        }
    }

</script>
<table class="search_default" id="lista_modulos">
	<caption>
			<strong>Modulos registrados</strong>			
	</caption>	
    <thead>
		<tr>
			<th>
				Modulo
			</th>
			<th>
				Autor
			</th>
			<th>
				Info
			</th>
            <th>
                <input type="checkbox" id="todos_ninguno" name="todos_ninguno" onclick="marcar_desmarcar();" >
            </th>
			<th>
                <input type="button" value="Reinstalar modulos seleccionados" class="btn btn_gear" onclick="instalar_modulos_lista();"/>
			</th>
		</tr>
    </thead>
	<tbody>
		{{START lista_modulos}}
        <tr class="[_row_class_]">
			<td>
                <b>[titulo]</b><br /><small>v:[version]<br/>[ultima_revision]</small>
			</td>
			<td>
				[autor]
			</td>
			<td>
				[info]
			</td>
            <td align="center">
                <input type="checkbox" value="[uuid]" name="modulos" id="modulos[]" />
            </td>
            <td align="center">
                &nbsp;&nbsp;&nbsp;
                <input class="btn btn_gear" type="button" onclick="mvcPost('System::admin_modulos_info','uuid=[uuid]','','instalar_modulo');"; value="Reinstalar" />
            </td>
		</tr>
		{{END lista_modulos}}
	</tbody>
</table>
<br />
<br />
<br />
<br />
<br />
