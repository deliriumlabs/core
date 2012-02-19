<?php
/*
Router class will have to analyze the request, and then load the correct command
 * Ultima Modificacio : Fri 24 Apr 2009 05:02:12 PM CDT
 */
class Router{

    private $registry;
    private $path;
    private $args = array();

    /**
     * Contructor de la clase
     *
     * @param Registry $registry El Objeto Registry
     */
    function __construct($registry) {
        $this->registry = $registry;
    }   

    /**
     * Es usado para especificar el directorio donde se encuentran los controles
     *
     * @param string $path El path donde se encuentran los controles.
     */
    function setPath($path) {
        //$path = trim($path, '/\\');
        $path .= DIRSEP;

        if (is_dir($path) == false) {
            throw new Exception ('Directorio de controladores Invalido: '.$path );
        }

        $this->path = $path;
    }   

    function find_controller($controller,&$cmd_path){					

        global $path_apps;  

        $dirName=$path_apps;
        if(!is_dir($dirName)){

            return false;
        }	
        $file=$controller.'.controller.php';		
        $this->searchRecurse($cmd_path,$file,$dirName);
    }

    function find_controller_core($dirName,$pattern,$more=true){                   
        $file='';
        $path=false;
        if(!is_dir($dirName)){
            return false;
        }
        $dirHandle = opendir($dirName);
        while(false !== ($incFile = readdir($dirHandle))) {
            if(filetype("$dirName/$incFile")=='file'){
                $file_name=$dirName.$incFile;                
                if(stristr($file_name,$pattern)){                    
                    $path=$dirName;
                    closedir($dirHandle);
                    return $path;
                }
            }elseif($incFile != '.' && $incFile != '..' && $incFile != '.DS_Store' && $incFile != '.svn' && filetype("$dirName/$incFile") == 'dir'){
                if($more && $incFile!='views'){

                    $path=$this->find_controller_core($dirName.$incFile.DIRSEP,$pattern,true);
                    if($path!=false){
                        closedir($dirHandle);
                        return $path;
                    }
                }
            }
        }
        closedir($dirHandle);

        return $path;

    }

    function searchRecurse(&$cmd_path,$file,$dirName,$more=true) {
        $path='';
        if(!is_dir($dirName)){	               		
            return false;
        }
        $dirHandle = opendir($dirName);     
        while(false !== ($incFile = readdir($dirHandle))) {           	
            if(filetype("$dirName$incFile")=='file'){
                echo "$dirName$incFile";				               
                if($file=="$dirName$incFile"){
                    closedir($dirHandle);										
                    $path="$dirName";
                    break;
                }									
            }elseif($incFile != '.' && $incFile != '..' && $incFile != '.DS_Store' && $incFile != '.svn' && filetype("$dirName/$incFile") == 'dir'){
                if($more && ($incFile!='views' && $incFile!='models')){						
                    $path=$this->searchRecurse($file,$dirName.$incFile.DIRSEP,true);				
                }
            }		
        }	  
        closedir($dirHandle);

        $cmd_path= $path;
    }

    /**	 	 	 
     * Primero obtenemos el valor de la variable $route pasada por query string
     * y lo separamos en partes usando la funcion explode con '/'.
     * Si el request es por ejemplo 'usuario/validar' este (el request) sera dividido en un array('usuario','validar'),.
     * Recorremos el array con un bucle foreach y verificamos si la parte es un directorio en caso de que sea un directorio
     * lo agregamos a la variable filepath y avanzamos a la siguiente parte.
     * Esto nos permite poner los controles en subdirectorios y utilizar la herencia de los controles
     * Si la parte no es un directorio, pero es un archivo, lo guardamos en la variable $controller y salimos del bucle dado que hemos encontrado el controller.
     * 
     * Despues de bucle nos aseguramos de haber encontrado un controller, en caso de no ser asi utilizamos el index
     * Despues procedemos a obtener la accion a executar.El controller es una clase que consiste en diferentes metodos,
     * y la accion que buscamos apunta a uno de esos metodos, si no se especifica alguna accion utilizamos la accion por default index()
     * Por ultimo obtenemos el path completo del controller concatenando el path,nombre del controller y la extension.	 
     * 
     * @param string $file Variable donde se obtendra el archivo
     * @param string $controller El controlador
     * @param string $action Variable donde se obtendra el metodo
     * @param string $args Variable donde se obtendran las partes
     */
    private function getController(&$file, &$controller, &$action, &$args) {
        # Primero obtenemos el valor de la variable $route pasada por query string
        $route = (empty($_REQUEST['do'])) ? '' : $_REQUEST['do'];

        if (empty($route)) { $route = 'index'; }

        # y lo separamos en partes usando la funcion explode con '/'.

        $route = trim($route, '/\\');

        $parts = explode('::', $route);        

        # Localizamos el controlador correcto
        $cmd_path = $this->path.$parts[0].DIRSEP;
        $cmd_path=str_replace('\\','/',$cmd_path);
        $cmd_path=str_replace('//','/',$cmd_path);
        $this->find_controller($parts[0],$cmd_path);

        # Recorremos el array con un bucle foreach y verificamos si la parte es un directorio
        foreach ($parts as $part) {
            $fullpath = $cmd_path . $part;
            # En caso de que sea un directorio
            # lo agregamos a la variable filepath y avanzamos a la siguiente parte.
            if (is_dir($fullpath)) {
                //$cmd_path .= $part . DIRSEP;
                array_shift($parts);
                continue;
            }

            # Si la parte no es un directorio, pero es un archivo, lo guardamos en la variable $controller y salimos del bucle dado que hemos encontrado el controller.
            if (is_file($fullpath . '.controller.php')) {
                $controller = $part;
                array_shift($parts);
                break;
            }
        }

        if(empty($controller)){
            $controller=$parts[0];
        }        

        # Despues de bucle nos aseguramos de haber encontrado un controller, en caso de no ser asi utilizamos el index
        if (empty($controller)) { $controller = 'index'; };

        # Despues procedemos a obtener la accion a executar.
        //$action = array_shift($parts);       
        $action = isset($parts[1])? $parts[1] : '';       


        # Si no se especifica alguna accion utilizamos la accion por default index()
        if (empty($action)) { $action = 'index'; }

        # Por ultimo obtenemos el path completo del controller concatenando el path,nombre del controller y la extension.
        $file = $cmd_path . $controller . '.controller.php';
    }

    function searchFilePathRecurse($dirName,$pattern,$more=true) {
        $file='';
        $path=false;
        if(!is_dir($dirName)){
            return false;
        }
        $dirHandle = opendir($dirName);
        while(false !== ($incFile = readdir($dirHandle))) {
            if(filetype("$dirName/$incFile")=='file'){
                $file_name=$dirName.$incFile;
                if(stristr($file_name,$pattern)){
                    $path=$dirName;
                    closedir($dirHandle);
                    return $path;
                }
            }elseif($incFile != '.' && $incFile != '..' && $incFile != '.DS_Store' && $incFile != '.svn' && filetype("$dirName/$incFile") == 'dir'){
                if($more && $incFile!='views'){

                    $path=$this->searchFilePathRecurse($dirName.$incFile.DIRSEP,$pattern,true);
                    if($path!=false){
                        closedir($dirHandle);
                        return $path;
                    }
                }
            }
        }
        closedir($dirHandle);

        return $path;
    }

    function searchFileRecurse($dirName,$pattern,$more=true) {
        $file='';
        $path=false;
        if(!is_dir($dirName)){
            return false;
        }
        $dirHandle = opendir($dirName);
        while(false !== ($incFile = readdir($dirHandle))) {
            if(filetype("$dirName/$incFile")=='file'){
                $file_name=$dirName.$incFile;
                if(strtoupper($incFile) == strtoupper($pattern)){
                    $path=$dirName;
                    closedir($dirHandle);
                    return $file_name;
                }
            }elseif($incFile != '.' && $incFile != '..' && $incFile != '.DS_Store' && $incFile != '.svn' && filetype("$dirName/$incFile") == 'dir'){
                if($more && $incFile!='views'){

                    $path=$this->searchFileRecurse($dirName.$incFile.DIRSEP,$pattern,true);
                    if($path!=false){
                        closedir($dirHandle);
                        return $path;
                    }
                }
            }
        }
        closedir($dirHandle);

        return $path;
    }
    function loadController($controller){

            $controller_path = '';
            $controller_path = $this->searchFileRecurse(PATH_APPS,$controller.'.controller.php');
            $in_core = 0;
            if($controller_path == ''){
                $controller_path = $this->searchFileRecurse(PATH_CORE_APPS,$controller.'.controller.php');
                $in_core = 1;
            }            

            $controller_path=str_replace('\\','/',$controller_path);
            $controller_path=str_replace('//','/',$controller_path);

            $strloc = strrpos($controller_path,'/apps/');

            $controller_path = substr($controller_path,$strloc+1);

            if($in_core==1){
                $controller_path='core/'.$controller_path;
            }

            if( filetype($controller_path) == 'file'){
                include($controller_path);
            }else{
                die ("<div class='error'>Controlador <i>$controller</i> no encontrado</div> ");
            }
    }
    /**
     * Carga el controlador correcto
     *
     */
    function delegate() {
        # Analizamos el route
        $this->getController($file, $controller, $action, $args);               

        $class = 'Controller_' . $controller;        

        if(!class_exists($class)){                    	
            $this->loadController($controller);
        }
        $controller_class = new $class($this->registry);

        // Verificar que el metodo este dispopnible
        if (is_callable(array($controller_class, $action)) == false) {
            die ("<div class='error'>Metodo <i>$action</i> no encontrado en el controller <i>$controller</i><br/>Error de llamada a: <i>$class::$action</i>  </div> ");
        }

        /* Determinar si hay que verificar que el usuario este logueado
         *  para ejecutar em metodo solicidato, y revisar si el metodo esta en la lista de metodos
         *  con permisos para sobrepasar la revision
        */
        if($controller_class->test_session == true){
            if(!in_array($action, $controller_class->bypass_session)){
                $this->loadController('Index');
                $index= new Controller_Index('','index');
                $index->test_session();
            }
        }

        // Ejecutamos la accion
        $controller_class->$action();
    }
}?>
