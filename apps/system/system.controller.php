<?php
if(!class_exists('Controller_System')){
    class Controller_System extends Controller_Base {
        var $bypass_session = array(
            'get_js_core',
            'get_js_search',
            'get_js_funciones',
            'get_css_core',
            'verify',
            'setup',
            'setup_step_1',
            'setup_do',
            'setup_modulo'
        );

        function get_js_core(){
            $this->renderTemplateExternal('core/deliriumkit/deliriumkit.js',array(),true);
        }

        function get_js_search(){
            $this->renderTemplateExternal('core/deliriumkit/lib/Search.js',array(),true);
        }

        function get_js_funciones(){
            $this->renderTemplateExternal('core/deliriumkit/lib/functions.js',array(),true);
        }

        function get_css_core(){
            $this->renderTemplateExternal('media/skin/'.$_REQUEST['skin'].'/style.css',array(),false);
        }

        function verify(){
            global $path_root;
            if ( !file_exists($path_root. '/mvc_config.php') ) {
                echo "FAILED";
            }else{
                echo "OK";
            }
        }

        function setup(){
            $this->renderTemplate("setup.tpl");
        }

        function setup_step_1(){

            $template['db_name']="";
            $template['db_username']="";
            $template['db_passwd']="";
            $template['db_hostname']="";
            $template['app_title']="";

            $template['msg']="";
            $this->renderTemplate("setup_step_1.tpl",$template);
        }

        function setup_do(){
            $setupdb=array();
            $db_name  = trim($_POST['db_name']);
            $db_username   = trim($_POST['db_username']);
            $db_passwd = trim($_POST['db_passwd']);
            $db_hostname  = trim($_POST['db_hostname']);

            $app_title= trim($_POST['app_title']);

            $configFile = file(PATH_CORE. 'mvc_config_example.php');
            $template=$_REQUEST;

            //Probar la conexion.
            @define('DB_NAME', $db_name);
            @define('DB_USER', $db_username);
            @define('DB_PASSWD', $db_passwd);
            @define('DB_HOST', $db_hostname);
            $ok=false;
            $cn=@mysql_connect(DB_HOST,DB_USER,DB_PASSWD);
            if(!$cn){

                setError("%CONEXION_FALLA%");
                $template['msg']=notificaciones();
                $this->renderTemplate("setup_step_1.tpl",$template);
            }else{
                setMsg("%CONEXION_OK%");
                if(!@mysql_select_db(DB_NAME)){		
                    setError("%DB_FALLA%");
                    if(!mysql_query("CREATE SCHEMA $db_name")){
                        setError("%DB_CREAR_ERROR%");					
                        $template['msg']=notificaciones();
                        $this->renderTemplate("setup_step_1.tpl",$template);
                    }else{
                        setMsg("%DB_CREADA%");
                        setMsg("%DB_OK%");
                        $template['msg']=notificaciones();
                        $ok=true;
                    }

                }else{
                    setMsg("%DB_OK%");
                    $template['msg']=notificaciones();
                    $ok=true;
                }						 
            }	

            $template['ejemplo']="";
            if($ok){						
                if (!is_writable(PATH_ROOT)) {
                    setError("%ESCRITURA_CONFIG_ERROR%");
                    $ejemplo="";
                    foreach ($configFile as $line_num => $line) {
                        switch (substr($line,0,16)) {
                        case "define('DB_NAME'":
                            $ejemplo.=str_replace("dbname", $db_name, $line);	                
                            break;
                        case "define('DB_USER'":
                            $ejemplo.=str_replace("'username'", "'$db_username'", $line);
                            break;
                        case "define('DB_PASSW":
                            $ejemplo.=str_replace("'password'", "'$db_passwd'", $line);
                            break;
                        case "define('DB_HOST'":
                            $ejemplo.=str_replace("localhost", $db_hostname, $line);
                            break;				
                        default:
                            if(stristr('$_SESSION["_TITULO_APLICACION_"]',$line)>=0){
                                $ejemplo.=str_replace("deliriumlabs", $app_title, $line);
                                break;					
                            }	
                            $ejemplo.=$line;
                            break;

                        }
                    }

                    $template['ejemplo']="
                        <textarea cols='150' rows='10'>$ejemplo</textarea>";
                }else{

                    $handle = fopen(PATH_ROOT. 'config.php', 'w');

                    foreach ($configFile as $line_num => $line) {
                        switch (substr($line,0,16)) {
                        case "define('DB_NAME'":
                            fwrite($handle, str_replace("dbname", $db_name, $line));	                
                            break;
                        case "define('DB_USER'":
                            fwrite($handle, str_replace("'username'", "'$db_username'", $line));
                            break;
                        case "define('DB_PASSW":
                            fwrite($handle, str_replace("'password'", "'$db_passwd'", $line));
                            break;
                        case "define('DB_HOST'":
                            fwrite($handle, str_replace("localhost", $db_hostname, $line));
                            break;	
                        default:
                            if(stristr('$_SESSION["_TITULO_APLICACION_"]',$line)>=0){
                                fwrite($handle, str_replace("deliriumlabs", $app_title, $line));
                                break;					
                            }
                            fwrite($handle, $line);
                        }
                    }
                    fclose($handle);
                    if(!@chmod(PATH_ROOT. 'config.php', 0666)){
                        setError("%ARCHIVO_CONFIG_SIN_PERMISOS%");

                    };
                    setMsg("%ARCHIVO_CONFIG_OK%");
                }

                $template['msg']=notificaciones();
                $template['core_modulos']="0";
                if(isset($_POST['bol_crear_db'])&&$_POST['bol_crear_db']=="true"){
                    include_once(PATH_CORE. 'base.sql.php');
                    $tmp_array_setup=array();
                    $tmp_array_setup['total']=sizeof($setupdb);
                    $i=1;
                    foreach ($setupdb as $key=>$value){						
                        $tmp_array_setup['modulo'][$i]['id']=$value['id'];
                        $tmp_array_setup['modulo'][$i]['label']=$value['label'];
                        $i++;
                    }
                    $template['core_modulos'] = array2json($tmp_array_setup);
                }
                $this->renderTemplate("setup_step_2.tpl",$template);
            }		

        }	

        function setup_modulo(){
            $setupdb=array();
            include_once(PATH_CORE. 'base.sql.php');
            $next=$_REQUEST['nid']+1;
            if(is_array($setupdb[$_REQUEST['id']]['sql'])){
                for($x=0;$x<sizeof($setupdb[$_REQUEST['id']]['sql']);$x++){
                    query($setupdb[$_REQUEST['id']]['sql'][$x]);
                }
            }else{
                query($setupdb[$_REQUEST['id']]['sql']);
            }

            setMsg("{$setupdb[$_REQUEST['id']]['label']} ok.");		
            $this->renderText("$next|".notificaciones());
        }

        function control_panel(){
            $this->renderTemplate("control_panel.tpl");		
        }

        function admin_modulos_info(){
            $_modulo=array();
            $this->setModel('System');
            $installer=$this->model->searchRecurse(PATH_APPS,"{$_REQUEST['uuid']}.install.php");
            if(sizeof($installer) == 0){
                $installer=$this->model->searchRecurse(PATH_CORE_APPS,"{$_REQUEST['uuid']}.install.php");
            }
            $installer=$installer[0];
            include_once($installer);
            foreach($_modulo as $key=>$value) {			
                $return_array[]=$value;
            }
            $this->renderText(array2json($return_array));
        }

        function admin_modulos(){
            $this->setModel("System");				
            $template['lista_modulos']=$this->model->list_installers();		
            $this->renderTemplate("control_panel_modules_list.tpl",$template);		
        }

        function admin_modulos_instalar(){
            $this->setModel("System");				
            $template['lista_modulos']=$this->model->list_new_installers();		
            $this->renderText(notificaciones());
            $this->renderTemplate("control_panel_modules_register.tpl",$template);		
        }

        function admin_modulo_instalar_menu(){
            $_modulo=array();
            $this->setModel('System');
            $installer=$this->model->searchRecurse(PATH_APPS,"{$_REQUEST['uuid']}.install.php");
            if(sizeof($installer) == 0){
                $installer=$this->model->searchRecurse(PATH_CORE_APPS,"{$_REQUEST['uuid']}.install.php");
            }
            include_once($installer[0]);
            $menu=$_modulo['modulo_'.$_REQUEST['uuid']]['menus'][$_REQUEST['menu']];

            $this->setModel('Menu');
            if($this->model->validar_menu_uuid($menu['uuid'])){
                $this->model->registrar_menu($menu);
            }
            setMsg("Instalado :".$menu['titulo']);
            $this->renderText(notificaciones());
        }

        function admin_modulo_instalar_paso(){
            $_modulo=array();
            $this->setModel('System');
            $installer=$this->model->searchRecurse(PATH_APPS,"{$_REQUEST['uuid']}.install.php");
            if(sizeof($installer) == 0){
                $installer=$this->model->searchRecurse(PATH_CORE_APPS,"{$_REQUEST['uuid']}.install.php");
            }
            include_once($installer[0]);
            $paso=$_modulo['modulo_'.$_REQUEST['uuid']]['paso_'.$_REQUEST['paso']];
            $paso['funcion']();
            setMsg($paso['label']);
            echo "|";
            $this->renderText(notificaciones());
        }

        function admin_modulo_registrar(){
            $_modulo=array();
            $this->setModel('System');
            $installer=$this->model->searchRecurse(PATH_APPS,"{$_REQUEST['uuid']}.install.php");
            if(sizeof($installer) == 0){
                $installer=$this->model->searchRecurse(PATH_CORE_APPS,"{$_REQUEST['uuid']}.install.php");
            }
            include_once($installer[0]);
            $modulo=$_modulo['modulo_'.$_REQUEST['uuid']];
            $this->model->registrar_modulo($modulo);
            setMsg("Registrado :".$modulo['titulo']);
            $this->renderText(notificaciones());
        }

        function view_admin_modulo_upload(){
            $this->renderTemplate("control_panel_modules_upload.tpl");
        }	

        function admin_modulos_upload(){
            $this->setModel("System");
            $this->renderText($this->model->descomprimir_modulo());
        }

    }
}
?>
