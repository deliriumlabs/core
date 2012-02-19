<textarea style="width:99%;height:98%;margin:0px auto;">
$_modulo['modulo_{modulo}']= array(
                                'uuid'=>"{modulo}",
                                'titulo'=>"TITULO",
                                'info'=>"INFO",
                                'autor'=>"AUTOR",
                                'version'=>"VERSION",
                                'ultima_revision'=>"ULTIMA_REVISION"
                                );

{{START lista_menus}}
$_modulo['modulo_{modulo}']['menus'][]=array(
                                'uuid'=>"[uuid]",
                                'titulo'=>"[titulo]",
                                'href'=>"[href]",
                                'onclick'=>"[onclick]",
                                'orden'=>"[orden]",
                                'parent_uuid'=>"[parent_uuid]"
                                );
{{END lista_menus}}

$_modulo['modulo_{modulo}']['pasos_instalacion']="1";

$_modulo['modulo_{modulo}']['paso_1']['label']="Creando Tablas";
$_modulo['modulo_{modulo}']['paso_1']['funcion']="instalar_tablas_{modulo}";

function instalar_tablas_{modulo}(){

}
</textarea>
