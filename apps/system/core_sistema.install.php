<?php
$_modulo['modulo_core_sistema']= array(
                                'uuid'=>"core_sistema",
                                'titulo'=>"CORE",
                                'info'=>"Nucleo Sistema",
                                'autor'=>"Omar Cobos",
                                'version'=>"1.0",
                                'ultima_revision'=>"Sun May 13 20:11:01 2012"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-sistema",
                                'titulo'=>"Sistema",
                                'href'=>"",
                                'onclick'=>"",
                                'orden'=>"0",
                                'parent_uuid'=>"0"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-passwd",
                                'titulo'=>"Cambiar contraseña",
                                'href'=>"",
                                'onclick'=>"Usuarios::view_change_passwd",
                                'orden'=>"1",
                                'parent_uuid'=>"core-sistema"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-salir",
                                'titulo'=>"Salir",
                                'href'=>"",
                                'onclick'=>"usuarios::logOut",
                                'orden'=>"1",
                                'parent_uuid'=>"core-sistema"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-sistema-configuracion",
                                'titulo'=>"Configuración",
                                'href'=>"",
                                'onclick'=>"",
                                'orden'=>"2",
                                'parent_uuid'=>"core-sistema"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-menu",
                                'titulo'=>"Menú",
                                'href'=>"",
                                'onclick'=>"core_menu::admin",
                                'orden'=>"1",
                                'parent_uuid'=>"core-sistema-configuracion"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-perfiles",
                                'titulo'=>"Perfiles",
                                'href'=>"",
                                'onclick'=>"Perfiles",
                                'orden'=>"1",
                                'parent_uuid'=>"core-sistema-configuracion"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-usuarios",
                                'titulo'=>"Usuarios",
                                'href'=>"",
                                'onclick'=>"usuarios",
                                'orden'=>"2",
                                'parent_uuid'=>"core-sistema-configuracion"
                                );


$_modulo['modulo_core_sistema']['menus'][]=array(
                                'uuid'=>"core-control-panel",
                                'titulo'=>"Panel de Control",
                                'href'=>"",
                                'onclick'=>"system::control_panel",
                                'orden'=>"3",
                                'parent_uuid'=>"core-sistema-configuracion"
                                );



$_modulo['modulo_core_sistema']['pasos_instalacion']="1";

$_modulo['modulo_core_sistema']['paso_1']['label']="Creando Tablas";
$_modulo['modulo_core_sistema']['paso_1']['funcion']="instalar_tablas_core_sistema";

function instalar_tablas_core_sistema(){
    $strSql = "
        CREATE TABLE IF NOT EXISTS core_tbl_log (
            id_log INT NOT NULL AUTO_INCREMENT ,
            ip INT(11) UNSIGNED NULL ,
            id_usuario INT NULL ,
            chr_tipo_evento VARCHAR(100) NULL ,
            txt_evento MEDIUMTEXT NULL ,
            dtm_sistema TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ,
            PRIMARY KEY (id_log) ,
            INDEX idx_tipo_evento (chr_tipo_evento ASC) ,
            INDEX idx_dtm_sistema (dtm_sistema ASC) )
        ENGINE = InnoDB
        ";
    query($strSql);

}

?>
