<?php
if(!class_exists('Controller_Perfiles')){
    class Controller_Perfiles extends Controller_Base {
    	
    	function index(){
    		$this->renderTemplate('view_perfiles_list.tpl');
    	}	
    	
    	function view_new(){
    		$this->renderTemplate('view_perfiles_new.tpl');		
    	}
    	
    	function save(){
    		$this->setModel('Perfiles');
    		$this->model->save($_REQUEST);
    		echo "&nbsp";
    	}
    	
    	function view_edit(){		
    		$this->setModel('Perfiles');
    		$template=$this->model->get_info($_REQUEST['id_perfil']);
    		$this->renderTemplate('view_perfiles_edit.tpl',$template);
    	}
    	
    	function edit(){
    		$this->setModel('Perfiles');
    		$this->model->edit($_REQUEST);
    	}
    	function view_details(){
    		$this->setModel('Perfiles');
    		$template=$this->model->get_info($_REQUEST['id_perfil']);
    		$this->renderTemplate('view_perfiles_details.tpl',$template);
    	}
    	
    	function view_set_acl(){
    		$this->setModel('Perfiles');
    		$template['perfiles']=$this->model->getMenuListGroups($_REQUEST['id_perfil']);
    		$this->renderTemplate('view_perfiles_set_acl.tpl',$template);
    	}
    	function set_acl(){
    		$this->setModel('Perfiles');
    		$this->model->set_acl($_REQUEST);
    	}
    }
}
?>