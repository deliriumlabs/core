<?php

$_modulo['modulo_delirium_reporter']= array(
                                'uuid'=>"delirium_reporter",
                                'titulo'=>"Delirium Reporter",
                                'info'=>"Modulo para el diseño y despliegue de reportes",
                                'autor'=>"Ruben Omar cobos Leal",
                                'version'=>"0.1",
                                'ultima_revision'=>"Tue 25 Aug 2009 03:51:51 PM CDT"
                                );


$_modulo['modulo_delirium_reporter']['menus'][]=array(
                                'uuid'=>"delirium_reporter",
                                'titulo'=>"Reportes",
                                'href'=>"",
                                'onclick'=>"",
                                'orden'=>"8",
                                'parent_uuid'=>"0"
                                );


$_modulo['modulo_delirium_reporter']['menus'][]=array(
                                'uuid'=>"delirium-reporter-ide",
                                'titulo'=>"Diseñador",
                                'href'=>"",
                                'onclick'=>"DeliriumDesigner",
                                'orden'=>"1",
                                'parent_uuid'=>"delirium_reporter"
                                );


$_modulo['modulo_delirium_reporter']['menus'][]=array(
                                'uuid'=>"delirium-reporter-list",
                                'titulo'=>"Listado",
                                'href'=>"",
                                'onclick'=>"DeliriumDesigner::listado",
                                'orden'=>"2",
                                'parent_uuid'=>"delirium_reporter"
                                );



$_modulo['modulo_delirium_reporter']['pasos_instalacion']="1";

$_modulo['modulo_delirium_reporter']['paso_1']['label']="Creando Tablas";
$_modulo['modulo_delirium_reporter']['paso_1']['funcion']="instalar_tablas_delirium_reporter";

function instalar_tablas_delirium_reporter(){
    $strSql ="
        CREATE TABLE IF NOT EXISTS designer_tbl_reportes (
            id_reporte INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
            uuid VARCHAR(255) NULL,
            uuid_modulo VARCHAR(255) NULL,
            chr_titulo VARCHAR(255) NULL,
            orientation VARCHAR(255) NULL,
            width INTEGER UNSIGNED NULL,
            height INTEGER UNSIGNED NULL,
            top_margin INTEGER UNSIGNED NULL,
            right_margin INTEGER UNSIGNED NULL,
            bottom_margin INTEGER UNSIGNED NULL,
            left_margin INTEGER UNSIGNED NULL,
            header_height INTEGER UNSIGNED NULL,
            content_height INTEGER UNSIGNED NULL,
            footer_height INTEGER UNSIGNED NULL,
            content_alias VARCHAR(255) NULL,
            token VARCHAR(32) NULL,
            PRIMARY KEY(id_reporte)
        )Engine=MyISAM;";
    query($strSql);

    $strSql ="
        CREATE TABLE IF NOT EXISTS designer_rel_reporte_sql (
            id_rel_sql INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
            id_reporte INTEGER UNSIGNED NOT NULL,
            alias VARCHAR(255) NULL,
            str_sql LONGTEXT NULL,
            PRIMARY KEY(id_rel_sql)
        )Engine=MyISAM;";
    query($strSql);

    $strSql ="
        CREATE TABLE IF NOT EXISTS designer_rel_reporte_objeto_propiedades (
            id_rel_objeto INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
            id_reporte INTEGER UNSIGNED NOT NULL,
            seccion VARCHAR(20) NULL,
            uuid_objeto VARCHAR(255) NULL,
            obj_propiedad VARCHAR(255) NULL,
            value LONGTEXT NULL,
            PRIMARY KEY(id_rel_objeto)
        )Engine=MyISAM;";
    query($strSql);
}


?>
