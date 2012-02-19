<?php
if(!class_exists('Controller_Usuarios')){
    class Controller_Usuarios extends Controller_Base{
        //Estas funciones no requerien validacion de password
        var $bypass_session = array('login');

        function view_login(){
            $template['notificaciones']=notificaciones();
            $this->renderTemplate('view_login.tpl',$template);
        }

        function login(){
            if (!isset($_SESSION['id_usuario'])) {
                $this->setModel('Usuarios');
                $this->model->login($_REQUEST);
        }
            echo "<script> location.href='index.php'<"."/script>";
        }

        function logout(){
            session_destroy();
            echo "<script> location.href='index.php'<"."/script>";
        }

        function index(){
            $this->renderTemplate('view_usuarios_list.tpl');
        }

        function view_new(){
            $this->setModel('Perfiles');
            $template['lista_perfiles']=$this->model->get_list();
            $this->renderTemplate('view_usuarios_new.tpl',$template);
        }
        
        function save(){
            $this->setModel('Usuarios');
            $this->model->full_save($_REQUEST);
        }

        function view_edit(){
            $this->setModel('Usuarios');
            $this->renderTemplate('view_usuarios_edit.tpl',$this->model->get_info($_REQUEST['id_usuario']));
        }

        function edit(){
            $this->setModel('Usuarios');
            $this->model->edit($_REQUEST);
        }

        function view_change_passwd(){
            $this->renderTemplate('view_usuarios_passwd_change.tpl');
        }

        function change_passwd(){
            $this->setModel('Usuarios');
            $this->model->change_passwd($_REQUEST);

        }

    }
}
?>
