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
       '.svn' 
   );
   while(false !== ($incFile = readdir($dirHandle))) {   		
		if(!in_array($incFile, $type_avoid) && !stristr($incFile,'install.php') && !stristr($incFile,'model.php') &&!stristr($incFile,'controller.php')){	           
            include($dirName.$incFile);
		}elseif(!in_array($incFile, $type_avoid) && !stristr($incFile,'install.php') && !stristr($incFile,'model.php') &&!stristr($incFile,'controller.php')){
			if($more && $incFile!='views'){						
                includeRecurse($dirName.$incFile.DIRSEP,true);				
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

function query($query){
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
    $result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
    return $result;
}

function query2array_old($query){
    $debuginfo = debug_backtrace();
    $debug_str = '<b>Archivo</b> : '.$debuginfo[0]['file'].'<br />';
    $debug_str .= '<b>Linea</b> : '.$debuginfo[0]['line'].'<br />';
	$result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
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
	$result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
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
	$result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
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
	$result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
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
	$result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
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
	$result=mysql_query($query) or die($debug_str.'<b>'.mysql_error().'</b>'.'<br/><b>SQL:</b><br /><pre >'.$query.'</pre>');
	$return_array=array();			
	while ($obj = mysql_fetch_object($result)) {
		foreach($obj as $key => $value) {				
			$return_array[$key]=$value;								
		} 
	}		
	return $return_array;
}
?>
