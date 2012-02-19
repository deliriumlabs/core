<script>
function edit(uuid, uuid_modulo){
    var uuid_ops = 'uuid='+uuid;
    uuid_ops += '&uuid_modulo='+uuid_modulo;
    mvcPost('DeliriumDesigner',uuid_ops, 'working_area');
	//_window({mvc:'DeliriumDesigner::view_edit',mvcparams:'id_reporte='+id_reporte,title:'%BTN_MODIFICAR%',height:200,modal:true});
}

function remove(id_reporte,chr_nombre){
	if(confirm('%CONFIRMAR_BORRAR%')==true){
        mvcPost('DeliriumDesigner::remove','id_reporte='+id_reporte, 'working_area', function(){
            mvcPost('DeliriumDesigner::listado','', 'working_area');
        });
	}
}

function view_import(){
	_window({mvc:'DeliriumDesigner::view_import',title:'%BTN_IMPORTAR%',height:200,modal:true});
}

var lista_reportes=new dk.delirium_search({
	id:'lista_reportes',
	tabla:""+
			"SELECT "+
			"	* "+
			"FROM "+ 
			"	designer_tbl_reportes "+
			"ORDER BY uuid_modulo",
	panel:'panel',
	titulo:'%TITULO_LISTA%',
	opciones:[
		{
			titulo:'%BTN_MODIFICAR%',
			onclick:'edit',
			css_class:'btn_editar',
			parametros:[
				{
					variable:'uuid',
					campo:'uuid'
                },
                {
					variable:'uuid_modulo',
					campo:'uuid_modulo'
				}
			]
		},
		{
			titulo:'%BTN_BORRAR%',
			onclick:'remove',
			css_class:'btn_borrar',
			parametros:[
				{
					variable:'id_reporte',
					campo:'id_reporte'
				},
				{
					variable:'uuid',
					campo:'uuid'
				}
			]			
		}
	],
	campos:{
		id_reporte:{
			titulo:'id_reporte',
			mostrar:false,
			para_busqueda:false,
			tipo:'numero'
		},
		uuid:{
			titulo:'%UUID%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		uuid_modulo:{
			titulo:'%UUID_MODULO%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		chr_titulo:{
			titulo:'%TITULO%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		}	
	}
});
window.onload=function(){
	lista_reportes.show();
}
</script>
<div id="sub_menu">
    <input type="button" class="btn btn_agregar" onclick="edit('','')" value="%BTN_NUEVO%" />
    <input type="button" class="btn btn_folder_table" onclick="view_import()" value="%BTN_IMPORTAR%" />
</div>
<div id="panel"></div>
<br />
<br />
<br />
<br />

