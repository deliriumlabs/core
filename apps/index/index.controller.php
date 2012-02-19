<?php
if(!class_exists('Controller_Index')){
    class Controller_Index extends Controller_Base {
        var $test_session = false;
    	
    	function index($contenido="",$head=array()){	
            $template['timestamp']="".time();	
            $template['skin']="default";  
    		$this->renderTemplate('index.tpl',$template);		
    	}	
    	
    	function validar_sesion(){
    		if (isset($_SESSION['id_usuario'])) {			
    			$this->renderTemplate('workspace.tpl');
    		}else{			
                $this->loadController('Usuarios');
    			$login= new Controller_Usuarios('','usuarios');
    			$login->view_login();
    		}				
    	}

    function test_session(){
         //debug_array($_SESSION);
        if (isset_or($_SESSION['id_usuario'], 0) > 0) {			
            //$_SESSION['id_usuario'] = "";
            return true;
        }else{			
            //debug_array($this);
            session_destroy();
            echo "<script> alert('Su session ha caducado!.'); window.onbeforeunload=null;location.href='index.php'<"."/script>";
        
        }				
    }

    	
    	
    	function acercade(){
    		echo "ISOERP 2.0";
    	}
    		
    }
}
?>
