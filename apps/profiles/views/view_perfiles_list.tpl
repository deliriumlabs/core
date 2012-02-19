<script>
function details(id_perfil){
	_window({mvc:'perfiles::view_details',mvcparams:'id_perfil='+id_perfil,height:'180'});
}

function edit(id_perfil){
	_window({mvc:'perfiles::view_edit',mvcparams:'id_perfil='+id_perfil,height:'180'});
}

function edit_acl(id_perfil){
	_window({mvc:'perfiles::view_set_acl',mvcparams:'id_perfil='+id_perfil});
}

var lista_perfiles=new dk.delirium_search({
	id:'lista_perfiles',
	tabla:"SELECT * FROM core_cat_perfiles ",
	panel:'panel',
	registros:'10',
	titulo:'%LISTA_PERFILES%',
	opciones:[
		{
			titulo:'%DETALLE%',
			onclick:'details',
			parametros:[
				{
					variable:'id_perfil',
					campo:'id_perfil'
				}
			]
		},
		{
			titulo:'%MODIFICAR%',
			onclick:'edit',
			parametros:[
				{
					variable:'id_perfil',
					campo:'id_perfil'
				}
			]
		},
		{
			titulo:'%MODIFICAR_PERMISOS%',
			onclick:'edit_acl',
			parametros:[
				{
					variable:'id_perfil',
					campo:'id_perfil'
				}
			]
		}
		
	],
	campos:{
		id_perfil:{
			titulo:'id_perfil',
			mostrar:false,
			para_busqueda:false,
			tipo:'numero'
		},
		chr_perfil:{
			titulo:'%PERFIL%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		txt_comentarios:{
			titulo:'%COMENTARIOS%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		}
	}
});
window.onload=function(){
	lista_perfiles.show();
}
</script>
<div id="sub_menu">
	<a href="javascript:void(0);" class="button" onclick="_window({mvc:'perfiles::view_new',modal:false,height:'180'});"><span>%REGISTRAR_PERFIL%</span></a>
</div>
<div id="panel"></div>
<br />
<br />
<br />
<br />