<?php
/*
 * Ultima Modificacion : Fri 24 Apr 2009 05:03:23 PM CDT
 */
	
_autoload('x');
function includeRecurse($dirName,$more=true) {
   if(!is_dir($dirName)){
       return false;
   }
   $dirHandle = opendir($dirName);
   $type_avoid= array(
        '.', 
        '..', 
        '.DS_Store', 
        '.svn',
        'views',
       'lang' 
   );
   while(false !== ($incFile = readdir($dirHandle))) {   		
        if(in_array($incFile, $type_avoid)){
            continue;
        }
		//if(!in_array($incFile, $type_avoid) && !stristr($incFile,'install.php') && !stristr($incFile,'model.php') &&!stristr($incFile,'controller.php')){	           
		if(!stristr($incFile,'install.php') && !stristr($incFile,'model.php') &&!stristr($incFile,'controller.php')){	           
            include($dirName.$incFile);
        }elseif(is_dir("$dirName/$incFile") ){
			if($more){						
                includeRecurse($dirName.$incFile.DIRSEP,true);				
			}
		}		
   }
   
   closedir($dirHandle);
}


function cache_controllers($dirName, $more=true) {
    if(!is_dir($dirName)){
        return false;
    }
    $dirHandle = opendir($dirName);
    $type_avoid= array(
        '.', 
        '..', 
        '.DS_Store', 
        '.svn', 
        'views',
        'lang' 
    );
    while(false !== ($incFile = readdir($dirHandle))) {
        if(in_array($incFile, $type_avoid)){
            continue;
        }
        if(stristr($incFile,'controller.php') ){
            $file_name=$dirName.$incFile;
            $controller = explode('.', strtolower($incFile));
            if(!isset($_SESSION['controllers'][$controller[0]])){
                $_SESSION['controllers'][$controller[0]] = $file_name;
            }
        }elseif(is_dir("$dirName/$incFile") ){
            if($more){
                cache_controllers($dirName.$incFile.DIRSEP, true);
            }
        }
    }
    closedir($dirHandle);
}

function _autoload($class_name) {
	
	global $path_includes;
	$dirName=$path_includes;
	if(!is_dir($dirName)){
	
	   return false;
	}	
	includeRecurse($dirName);
}

function query($query, $debuginfo = ''){
    if($debuginfo === ''){
        $debuginfo = debug_backtrace();
    }
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    if ( defined('DEBUG') && TRUE===DEBUG ) { 
        $result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
    }else{
        $result=mysql_query($query) or die('Ha ocurrido un error notif&iacute;quelo al administrador del sistema.');
    }
    return $result;
}

function query2array_old($query){
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result = query($query, $debuginfo);
	$tmp_array=array();
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
		foreach($obj as $key => $value) {				
			$tmp_array[$key]=$value;								
		} 
		$return_array[]=$tmp_array;
	}		
	return $return_array;
}

function query2array($query){
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result = query($query, $debuginfo);
	$tmp_array=array();
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
        $tmp_array = (array) $obj;
		$return_array[]=$tmp_array;
	}		
    mysql_free_result($result);
    unset($result);
	return $return_array;
}

function query2SingleArray($query){
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result = query($query, $debuginfo);
	$tmp_array=array();
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
		foreach($obj as $key => $value) {				
			$tmp_array[]=$value;								
		} 
		$return_array[]=$tmp_array;
	}		
	return $return_array;
}

function query2ArrayRow($query){
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result = query($query, $debuginfo);
	$tmp_array=array();
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
		foreach($obj as $key => $value) {				
			$tmp_array=$value;								
		} 
		$return_array[]=$tmp_array;
	}		
	return $return_array;
}

function query2vars($query){	
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result = query($query, $debuginfo);
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
			$return_array = (array) $obj;
	}		
    mysql_free_result($result);
    unset($result);
	return $return_array;
}

function query2vars_old($query){	
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result = query($query, $debuginfo);
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
		foreach($obj as $key => $value) {				
			$return_array[$key]=$value;								
		} 
	}		
	return $return_array;
}

function user_log($chr_tipo_evento = '', $txt_evento = ''){
    $id_usuario = isset_or($_SESSION['id_usuario'], 0);
    $strSql = "
        INSERT INTO core_tbl_log 
        (ip, id_usuario, chr_tipo_evento, txt_evento) 
        VALUES 
        (INET_ATON('{$_SERVER['REMOTE_ADDR']}'), $id_usuario, '$chr_tipo_evento', '$txt_evento') 
        ";
    @mysql_query($strSql);
    
}
?>
