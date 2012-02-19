<?php
//Definimos la constante del separador de directorios (/ o \)
define ('DIRSEP', DIRECTORY_SEPARATOR);

//Obtener el path relativo del sitio, en que carpeta se encuentra
$path_root = realpath(dirname(__FILE__) . DIRSEP . '..' . DIRSEP) . DIRSEP;
define('PATH_ROOT',$path_root);

//Definir el directorio donde se encuentran las aplicaciones
$path_apps = $path_root."apps".DIRSEP;
define('PATH_APPS',$path_apps);

//Definir el directorio de archivos externos
$path_extra = $path_root."extra".DIRSEP;
define('PATH_EXTRA',$path_extra);

//Definir el directorio de los archivos del framework
$path_core = $path_root."core".DIRSEP;
define('PATH_CORE',$path_core);

//Definir el directorio donde se encuentran las aplicaciones del framework
$path_core_apps = $path_core."apps".DIRSEP;
define('PATH_CORE_APPS',$path_core_apps);

//Definir el directorio donde se encuentran las aplicaciones extra del framework
$path_core_extra = $path_core."extra".DIRSEP;
define('PATH_CORE_EXTRA',$path_core_extra);

//Definir el directorio de los archivos que se incluyen en autoload
$path_includes = $path_core."includes".DIRSEP;
define('PATH_INCLUDES',$path_includes);

$dir_includes="core/includes/";
define('DIR_INCLUDES',$dir_includes);

//Incluir el archivo con las funciones basicas
include($path_core."base.inc.php");

//Incluir el archivo que contiene las directivas de include definidas por el usuario
//copiar esta linea a includes php para override el timezone
//http://www.php.net/manual/es/timezones.php
date_default_timezone_set("America/Monterrey");
include($path_root."includes.php");

//Incluir el archivo que contiene las funciones definidas por el usuario
include($path_root."functions.php");

//Variable tipo token
$_SESSION['_SID_']=time();

//verificar que el core este configurado
if(file_exists($path_root."config.php")){

    include($path_root."config.php");
    if( is_file($path_root."site_config.php" )){
        include($path_root."site_config.php");
    }

    //VERIFICAR SI LA APLICACION UTILIZA BASE DE DATOS
    if(USEDB){
        $cn=mysql_connect(DB_HOST,DB_USER,DB_PASSWD) or die("No se pudo conectar");
        if(!$cn){
            die("No se pudo conectar");
        }
        mysql_select_db(DB_NAME) or die("No se encontro la base de datos");
        $_REQUEST=sql_str_safe($_REQUEST,1);
        query("SET  lc_time_names = 'es_ES'");
    }else{
        $_REQUEST=sql_str_safe($_REQUEST);
    }
}else{
    //Si no existe el archivo de configuracion reescribir el request hacia en modulo config del core
    if(isset($_REQUEST['do'])){
        $_REQUEST['do']=stristr($_REQUEST['do'],"system::setup") >= 0 ? $_REQUEST['do'] : "System::setup";
    }else{
        $_REQUEST['do']="System::setup";
    }
}
    /*
    Ahora definimos un objeto de registro para mantener todos los datos globales.
    la clase registry es usada para transportar datos globales sin tener que definirlas con global
     */
$registry = new Registry;

if(isset($_REQUEST['opener'])){
    $registry->set ('opener', $_REQUEST['opener']);
}

//Cargar el ruteador
$router = new Router($registry);
$registry->set ('router', $router);

//Especificar elpath de los controles
$router->setPath($path_apps);

//Delegar
$registry->set('path_root', $path_root);
$router->delegate();

//Cerrar la conexion
if(isset_or($cn, false) != false){
	mysql_close($cn);
	unset($cn);
}
/*
if(file_exists($path_root."config.php")){
    if(USEDB){
    }
}
 */
?>
