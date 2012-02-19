<?php
$setupdb['menu']['id']="menu";
$setupdb['menu']['label']="Crear Tabla Menu";
$setupdb['menu']['sql'][]="DROP TABLE IF EXISTS core_cat_menu";
$setupdb['menu']['sql'][]="
	CREATE TABLE  `core_cat_menu` (
	  `id_menu` int(10) unsigned NOT NULL auto_increment,
	  `uuid` varchar(255) default NULL,
	  `titulo` varchar(255) default NULL,
	  `href` varchar(255) default NULL,
	  `onclick` varchar(255) default NULL,
	  `parent_uuid` varchar(255) default NULL,
	  `orden` int(10) unsigned default NULL,
	  `habilitado` tinyint(3) unsigned default '1',
	  `en_ventana` tinyint(3) unsigned default '0',
	  PRIMARY KEY  (`id_menu`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$setupdb['menu']['sql'][]="
INSERT INTO `core_cat_menu` VALUES  (null,'core-sistema','%SISTEMA%','','','0',0,1,0),
 (null,'core-sistema-configuracion','%CONFIGURACION%','','','core-sistema',1,1,0),
 (null,'core-menu','%MENU%','','core_menu::admin','core-sistema-configuracion',1,1,0),
 (null,'core-perfiles','%PERFILES%','','Perfiles','core-sistema-configuracion',1,1,0),
 (null,'core-usuarios','%USUARIOS%','','usuarios','core-sistema-configuracion',2,1,0),
 (null,'core-passwd','%CAMBIAR_PASSWD%','','Usuarios::view_change_passwd','core-sistema',1,1,1),
 (null,'core-salir','%SALIR%','','usuarios::logOut','core-sistema',2,1,0),
 (null,'core-control-panel','%PANEL_CONTROL%','','system::control_panel','core-sistema-configuracion',3,1,0);
    ";

$setupdb['perfiles']['id']="perfiles";
$setupdb['perfiles']['label']="Crear Tabla Perfiles";
$setupdb['perfiles']['sql'][]="DROP TABLE IF EXISTS core_cat_perfiles";
$setupdb['perfiles']['sql'][]="
CREATE TABLE  `core_cat_perfiles` (
  `id_perfil` int(10) unsigned NOT NULL auto_increment,
  `chr_perfil` varchar(100) default NULL,
  `txt_comentarios` mediumtext,
  PRIMARY KEY  (`id_perfil`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$setupdb['perfiles_permisos']['id']="perfiles_permisos";
$setupdb['perfiles_permisos']['label']="Crear Tabla Perfiles-Permisos";
$setupdb['perfiles_permisos']['sql'][]="DROP TABLE IF EXISTS core_tbl_perfiles_permisos";
$setupdb['perfiles_permisos']['sql'][]="
CREATE TABLE `core_tbl_perfiles_permisos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uuid` varchar(255) default NULL,
  `id_perfil` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `core_tbl_perfiles_permisos_FKIndex1` (`id_perfil`),
  KEY `core_tbl_perfiles_permisos_FKIndex2` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$setupdb['usuarios']['id']="usuarios";
$setupdb['usuarios']['label']="Crear Tabla Usuarios";
$setupdb['usuarios']['sql'][]="DROP TABLE IF EXISTS core_tbl_usuarios";
$setupdb['usuarios']['sql'][]="
CREATE TABLE  `core_tbl_usuarios` (
  `id_usuario` int(10) unsigned NOT NULL auto_increment,
  `chr_nombre_usuario` varchar(100) default NULL,
  `chr_passwd` varchar(255) default NULL,
  `id_perfil` int(10) unsigned default '0',
  `chr_nombres` varchar(100) default NULL,
  `chr_paterno` varchar(100) default NULL,
  `chr_materno` varchar(100) default NULL,
  `chr_email` varchar(255) default NULL,
  `bol_verificado` BOOL default 0,
  PRIMARY KEY  (`id_usuario`),
  KEY `core_cat_usuarios_FKIndex1` (`id_perfil`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

$setupdb['modulos']['id']="modulos";
$setupdb['modulos']['label']="Crear Tabla Modulos";
$setupdb['modulos']['sql'][]="DROP TABLE IF EXISTS core_cat_modulos";
$setupdb['modulos']['sql'][]="
CREATE TABLE `core_cat_modulos` (
  `id_modulo` INTEGER  NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255)  NOT NULL,
  `autor` VARCHAR(255)  NOT NULL,
  `version` VARCHAR(25)  NOT NULL,
  `ultima_revision` VARCHAR(25)  NOT NULL,
  `info` MEDIUMTEXT  NOT NULL,
  `uuid` VARCHAR(255)  NOT NULL,
  PRIMARY KEY (`id_modulo`)
)
ENGINE = MyISAM;
";


$setupdb['next_menu_order']['id']="next_menu_order";
$setupdb['next_menu_order']['label']="Crear Funcion Next Menu Order";
$setupdb['next_menu_order']['sql'][]="DROP FUNCTION IF EXISTS `next_menu_order`";
$setupdb['next_menu_order']['sql'][]="
CREATE FUNCTION `next_menu_order`(_parent_uuid VARCHAR(255) ) RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE next_id INT;
	select max(orden) into next_id from core_cat_menu where parent_uuid=_parent_uuid;
	IF isnull(next_id)THEN
		BEGIN
			select 0 into next_id;
		END;
	END IF;
	return next_id+1;

END";

$setupdb['funcion_nexuserid']['id']="funcion_nexuserid";
$setupdb['funcion_nexuserid']['label']="Crear Funcion Next user id";
$setupdb['funcion_nexuserid']['sql'][]="DROP FUNCTION IF EXISTS `next_user_id`";
$setupdb['funcion_nexuserid']['sql'][]="
CREATE FUNCTION `next_user_id`() RETURNS int(11)
    DETERMINISTIC
BEGIN
	DECLARE next_id INT;
	select max(id_usuario) into next_id from core_tbl_usuarios;

	return next_id+1;

END";

$setupdb['cargar_usuarios']['label']=" Cargar datos: usuarios iniciales";
$setupdb['cargar_usuarios']['id']="cargar_usuarios";
$setupdb['cargar_usuarios']['sql'][]="
INSERT INTO
	`core_tbl_usuarios`
		(`id_usuario`,`chr_nombre_usuario`,`chr_passwd`,`id_perfil`, `bol_verificado`)
	VALUES
	 (1,'root','63a9f0ea7bb98050796b649e85481845',0, 1)";

?>
