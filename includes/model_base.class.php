<?php
class Model_Base{	
	private $registry;
	private $vars = array();
	var $views=array();
	
	function __construct() {
		//$this->registry = $registry;				
	}			
	
	function show($name) {        
        $path = site_path . 'views' . DIRSEP . $name ;

        if (file_exists($path) == false) {
                trigger_error ('La vista `' . $name . '` no esiste.', E_USER_NOTICE);
                return false;
        }

        // Load variables
        foreach ($this->vars as $key => $value) {
                $$key = $value;
        }
        require_once ($path);               
	}
	function setView($view){
		$this->view=$view;
	}
	function refreshView(){		
		$refresh="<script> ;window.onload=function(){";
		
		for($x=0;$x<sizeof($this->views);$x++){						
			$refresh.="if($('{$this->views[$x]}')){";	
			$refresh.="delirium_window_list['{$this->views[$x]}'].refresh();";		
			$refresh.="}";
		}
		
		$refresh.='}</'.'script>';
		echo $refresh;
	}	
	
}
?>