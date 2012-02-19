<script type="text/javascript">
    
var lista_preview=new dk.delirium_search({
	id:'lista_preview',
	tabla:""+
			"SELECT "+
			"	*,if(bol_virtual=1,'%CHR_VIRTUAL%','%CHR_FISICO%') chr_tipo, "+
            "   CASE chr_uso "+
            "       WHEN 'v' THEN '%VENTAS%' "+
            "       WHEN 'p' THEN '%PERSONAL%' "+
            "       ELSE '%VENTAS%' "+
            "   END AS chr_uso_fto "+
			"FROM "+ 
			"	inventarios_cat_almacenes "+
			"WHERE "+
			"	bol_activo=1 "+
			"AND"+
			"    id_almacen_fisico=0 "+			
			"ORDER BY chr_nombre",
	panel:'panel',
	titulo:'%TITULO_LISTA%',
	opciones:[
		{
			titulo:'%BTN_DETALLE%',
			onclick:'details',
			css_class:'btn_detalle',
			parametros:[
				{
					variable:'id_almacen',
					campo:'id_almacen'
				}
			]
		},
		{
			titulo:'%BTN_MODIFICAR%',
			onclick:'edit',
			css_class:'btn_editar',
			parametros:[
				{
					variable:'id_almacen',
					campo:'id_almacen'
				}
			]
		},
        {
            titulo:'%BTN_VIRTUALES%',
            onclick:'listar_virtuales',
            css_class:'btn_editar',
            parametros:[
                {
                    variable:'id_almacen',
                    campo:'id_almacen'
                },
				{
                    variable:'chr_clave',
                    campo:'chr_clave'
                },{
                    variable:'chr_nombre',
                    campo:'chr_nombre'
                },{
                    variable:'chr_uso',
                    campo:'chr_uso'
                }
            ]
        },
		{
			titulo:'%BTN_BORRAR%',
			onclick:'remove',
			css_class:'btn_borrar',
			parametros:[
				{
					variable:'id_almacen',
					campo:'id_almacen'
				},
				{
					variable:'chr_nombre',
					campo:'chr_nombre'
				}
			]			
		}
	],
	campos:{
		id_almacen:{
			titulo:'id_almacen',
			mostrar:false,
			para_busqueda:false,
			tipo:'numero'
		},
		chr_uso:{
			titulo:'%CHR_USO%',
			mostrar:false,
			tipo:'texto',
			para_busqueda:false,
			orden:'asc'
		},
		chr_clave:{
			titulo:'%CHR_CLAVE%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		chr_nombre:{
			titulo:'%CHR_NOMBRE%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		chr_tipo:{
			titulo:'%CHR_TIPO%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		},
		chr_uso_fto:{
			titulo:'%CHR_USO%',
			mostrar:true,
			tipo:'texto',
			para_busqueda:true,
			orden:'asc'
		}	
	}
});
window.onload=function(){
	lista_preview.show();
}

</script>
