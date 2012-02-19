<?php
if(!class_exists('Controller_Core_Menu')){
    class Controller_Core_Menu extends Controller_Base {
        function index(){
            $this->setModel("menu");
            $menu['menu']=$this->model->generar_menu();

            $this->renderTemplate("menu.tpl",$menu);
        }

        function admin(){
            $this->renderTemplate("admin.tpl");
        }

        function admin_lista(){
            $this->setModel("menu");
            $menu['lista']=$this->model->obtener_submenus($_REQUEST['parent_uuid'],$_REQUEST['deep']);
            $menu['parent_uuid']=$_REQUEST['parent_uuid'];
            $menu['deep']=$_REQUEST['deep'];  		 
            $this->renderTemplate("admin_lista_raiz.tpl",$menu);
        }

        function view_menu_nuevo(){
            $this->renderTemplate("view_menu_nuevo.tpl",$_REQUEST);
        }

        function menu_nuevo(){
            $this->setModel("menu");
            $this->model->registrar_menu($_REQUEST);		
        }

        function view_menu_editar(){
            $this->setModel("menu");		
            $menu=$this->model->menu_info($_REQUEST['uuid']);
            $menu['parent_uuid']=$_REQUEST['parent_uuid'];
            $menu['deep']=$_REQUEST['deep']; 
            $this->renderTemplate("view_menu_editar.tpl",$menu);
        }

        function menu_editar(){
            $this->setModel("menu");
            $this->model->editar_menu($_REQUEST);
        }

        function menu_subir_orden(){
            $this->setModel("menu");       
            $this->model->menu_subir_orden($_REQUEST['uuid'],$_REQUEST['parent_uuid']);
            $menu['lista']=$this->model->obtener_submenus($_REQUEST['parent_uuid'],0);        
            $menu['parent_uuid']=$_REQUEST['parent_uuid'];
            $this->renderTemplate("admin_lista_raiz.tpl",$menu);
        }

        function menu_bajar_orden(){
            $this->setModel("menu");       
            $this->model->menu_bajar_orden($_REQUEST['uuid'],$_REQUEST['parent_uuid']);
            $menu['lista']=$this->model->obtener_submenus($_REQUEST['parent_uuid'],0);        
            $menu['parent_uuid']=$_REQUEST['parent_uuid'];
            $this->renderTemplate("admin_lista_raiz.tpl",$menu);
        }

        function menu_eliminar(){
            $this->setModel("menu");       
            $this->model->menu_eliminar_recursivo($_REQUEST['uuid']);
        }

        function view_menu_exportar(){
            $this->setModel("menu");
            $menu['lista_menus']=$this->model->obtener_menu_recursivo($_POST['uuid']);
            $menu['modulo'] = $_POST['uuid'];
            $this->renderTemplate('view_exportar.tpl',$menu);
        }
    }
}
?>
