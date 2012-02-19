<script>
function details(id_usuario){
	_window({mvc:'usuarios::view_details',mvcparams:'id_usuario='+id_usuario,title:'Detalle de Usuario',height:150});
}

function edit(id_usuario){
	_window({mvc:'usuarios::view_edit',mvcparams:'id_usuario='+id_usuario,title:'Modificar Datos de Usuario',height:150});
}

var lista_usuarios=new dk.delirium_search({
	id:'lista_usuarios',
	tabla:"SELECT u.*,p.chr_perfil,CONCAT(chr_paterno,char(32),chr_materno,char(32),chr_nombres) as nombre_completo FROM core_tbl_usuarios u INNER JOIN core_cat_perfiles p on u.id_perfil=p.id_perfil",
	panel:'panel',
	registros:'10',
	titulo:'%CATALOGO_USUARIOS%',
	opciones:[		
		{
			titulo:'%MODIFICAR%',
			onclick:'edit',
			parametros:[
				{
					variable:'id_usuario',
					campo:'id_usuario'
				}
			]
		}
	],
	campos:{
		id_usuario:{
			titulo:'id_usuario',
			mostrar:false,
			para_busqueda:false,
			tipo:'numero'
		},
		chr_nombre_usuario:{
			titulo:'%NOMBRE_USUARIO%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		nombre_completo:{
			titulo:'%NOMBRE_COMPLETO%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		chr_perfil:{
			titulo:'%PERFIL%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		}
	}
});
window.onload=function(){
	lista_usuarios.show();
}
</script>
<div id="sub_menu">
	<a href="javascript:void(0);" class="button" onclick="_window({mvc:'usuarios::view_new',modal:false,title:'Registrar Usuario Nuevo',height:150});"><span>%REGISTRAR_USUARIO%</span></a>
</div>
<div id="panel"></div>
<br />
<br />
<br />
<br />
