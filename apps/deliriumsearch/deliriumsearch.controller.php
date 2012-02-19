<?php
if(!class_exists('Controller_DeliriumSearch')){
class Controller_DeliriumSearch extends Controller_Base {
	function index(){		
		$this->setModel('DeliriumSearch');	
		
		$excel_export=isset($_REQUEST['excel'])?$_REQUEST['excel']:0;
		if($excel_export==1){
			header("Content-type: application/vnd.ms-excel");
			header("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: no-cache, must-revalidate"); 			
			header("Pragma: no-cache");			
			header("Content-Disposition: attachment; filename=x.xls");
            @header('Content-Type: text/html; charset=iso-8859-1');
			
			$this->renderTemplate('view_result_search_xls.tpl',$this->model->search($_REQUEST),false);	
		}else{
            if($_REQUEST['registros']==-1){
                
                $this->renderTemplate('view_result_search_print.tpl',$this->model->search($_REQUEST));
            }else{
            	$this->renderTemplate('view_result_search.tpl',$this->model->search($_REQUEST));
            }	
		}	
		
	}
	function search(){		
		$this->setModel('DeliriumSearch');	
		$this->renderTemplate('view_result_search.tpl',$this->model->search($_REQUEST));
	}	
	function getsearch($data){		
		$this->setModel('DeliriumSearch');	
		return $this->getTemplate('view_result_search.tpl',$this->model->searchweb($data));
	}
}
}
?>
