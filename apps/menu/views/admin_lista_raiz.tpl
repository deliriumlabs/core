<style type="text/css" media="screen">
    .menu_lista_container a{
        display:inline-block;
        min-height:20px;
    }
</style>

<div class="panel_buttons">
<input type="button" class="btn btn_add" value="Agregar una opción principal" onclick="_window({mvc:'core_menu::view_menu_nuevo',mvcparams:'parent_uuid={parent_uuid}&deep={deep}',height:160,width:260,title:'Nueva opción'})">

</div>
{{START lista}}
<div style="margin:3px;padding:3px;" class="internal menu_lista_container">
	[titulo]<br>
	&nbsp;&nbsp;&nbsp;&nbsp;
	[espacio]
	<a class="btn btn_expandir [container]" href="javascript:void(0);" onclick="mvcPost('core_menu::admin_lista','parent_uuid=[uuid]&deep=[deep]','[uuid]');$toggle('[uuid]');" title="Expandir">
		<span>+</span>	
	</a>
	<a class="btn btn_subir" href="javascript:void(0);" onclick="mvcPost('core_menu::menu_subir_orden','uuid=[uuid]&parent_uuid=[parent_uuid]','[parent_uuid]','regenerar_menu');" title="Subir">
		<span>Subir</span>
	</a>
	<a class="btn btn_bajar" href="javascript:void(0);" onclick="mvcPost('core_menu::menu_bajar_orden','uuid=[uuid]&parent_uuid=[parent_uuid]','[parent_uuid]','regenerar_menu');" title="Bajar">
		<span>Bajar</span>
	</a>					
	<a class="btn btn_editar" href="javascript:void(0);" onclick="_window({mvc:'core_menu::view_menu_editar',mvcparams:'uuid=[uuid]&parent_uuid={parent_uuid}&deep={deep}',height:160,width:260,title:'Modificar opción'});" title="Modificar Opcion">
		<span>Editar</span>
	</a>
	<a class="btn btn_borrar" href="javascript:void(0);" onclick="confirmar_eliminar('[uuid]','[parent_uuid]','{deep}');" title="Eliminar Opcion">
		<span>Eliminar</span>
	</a>
	<a class="btn btn_gear" href="javascript:void(0);" onclick="_window({mvc:'core_menu::view_menu_exportar',mvcparams:'uuid=[uuid]&parent_uuid=[parent_uuid]&deep=[deep]',height:500,width:500,title:'Exportar'}); "title="Exportar">
		<span>Exportar Instalador</span>
	</a>
	<div id="[uuid]" class="container" style="display:none;">
		
	</div>
</div>
{{END lista}}
