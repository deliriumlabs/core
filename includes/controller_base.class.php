<?php
/**
 * Clase padre para los controles,
 * Guarda una copia de la clase Registry y se asegura de que todos los controllers tengan un metodo index.
 * Ultima Modificacion :Fri 24 Apr 2009 05:35:16 PM CDT 
 */
class Controller_Base{
    protected $registry;
    protected $view;
    var $model;
    private $app_path;
    public $apps_path;
    public $app_name;
    var $test_session = true;
    var $bypass_session = array();

    function __construct($registry="",$app="") {
        global $path_apps;
        if (!is_object($registry)){
            $this->registry = new Registry;
            $this->app_path=$path_apps.$app.DIRSEP;
            $this->apps_path=$path_apps;
        }else{
            $this->registry = $registry;
        }

        # Primero obtenemos el valor de la variable $route pasada por query string
        $route = (empty($_REQUEST['do'])) ? '' : $_REQUEST['do'];

        if (empty($route)) { $route = 'index'; }

        # y lo separamos en partes usando la funcion explode con '/'.

        $route = trim($route, '/\\');

        $parts = explode('::', $route);

        # Localizamos el controlador correcto
        $cmd_path = $path_apps.$parts[0].DIRSEP;
        $cmd_path=str_replace("\\","/",$cmd_path);
        $cmd_path=str_replace("//","/",$cmd_path);

        $in_core = 0;
        if($app!=""){
            $this->app_name=$app;

            $this->app_path=$this->searchFilePathRecurse(PATH_APPS,$app.'.controller.php');
            if($this->app_path==""){
                $this->app_path=$this->searchFilePathRecurse(PATH_CORE_APPS,$app.'.controller.php');
                $in_core = 1;
            }            
        }else{
            $this->app_name=$parts[0];
            $this->app_path=$this->searchFilePathRecurse(PATH_APPS,$parts[0].'.controller.php');

            if($this->app_path==""){
                $this->app_path=$this->searchFilePathRecurse(PATH_CORE_APPS,$parts[0].'.controller.php');
                $in_core = 1;
            }             
        }
        $this->app_path=str_replace("\\","/",$this->app_path);
        $this->app_path=str_replace("//","/",$this->app_path);

        $this->app_dir_name=explode("/",$this->app_path);

        if($in_core==1){
            $this->app_dir_name='core/apps/'.$this->app_dir_name[sizeof($this->app_dir_name)-2].'/views';
        }else{
            $this->app_dir_name='apps/'.$this->app_dir_name[sizeof($this->app_dir_name)-2].'/views';
        }

        $this->apps_path=$path_apps;
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
                if(strtoupper($incFile) == strtoupper($pattern)){
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

    function setModel($model=''){
        $model_path = '';
        $model_path = $this->searchFileRecurse(PATH_APPS,$model.'.model.php');
        $in_core = 0;
        if($model_path == ''){
            $model_path = $this->searchFileRecurse(PATH_CORE_APPS,$model.'.model.php');
            $in_core = 1;
        }            

        $model_path=str_replace("\\","/",$model_path);
        $model_path=str_replace("//","/",$model_path);

        $strloc = strrpos($model_path,'/apps/');

        $model_path = substr($model_path,$strloc+1);

        if($in_core==1){
            $model_path='core/'.$model_path;
        }
        // Inicializar la clase
        $class = 'Model_' . $model;

        if(!class_exists($class)){
            include ($model_path);
        }

        $this->model=new $class();

        return true;
    }

    function setModelTo($model=''){
        $model_path = '';
        $model_path = $this->searchFileRecurse(PATH_APPS,$model.'.model.php');
        $in_core = 0;
        if($model_path == ''){
            $model_path = $this->searchFileRecurse(PATH_CORE_APPS,$model.'.model.php');
            $in_core = 1;
        }            

        $model_path=str_replace("\\","/",$model_path);
        $model_path=str_replace("//","/",$model_path);

        $strloc = strrpos($model_path,'/apps/');

        $model_path = substr($model_path,$strloc+1);

        if($in_core==1){
            $model_path='core/'.$model_path;
        }

        // Inicializar la clase
        $class = 'Model_' . $model;

        if(!class_exists($class)){
            include ($model_path);
        }


        $this->$model=new $class();

        return true;
    }

    function loadController($controller){

        $controller_path = '';
        $controller_path = $this->searchFileRecurse(PATH_APPS,$controller.'.controller.php');
        $in_core = 0;
        if($controller_path == ''){
            $controller_path = $this->searchFileRecurse(PATH_CORE_APPS,$controller.'.controller.php');
            $in_core = 1;
        }            

        $controller_path=str_replace("\\","/",$controller_path);
        $controller_path=str_replace("//","/",$controller_path);

        $strloc = strrpos($controller_path,'/apps/');

        $controller_path = substr($controller_path,$strloc+1);

        if($in_core==1){
            $controller_path='core/'.$controller_path;
        }

        include ($controller_path);
    }

    function renderTemplate($template,$data=array(),$writeheader=true){
        if($writeheader && !headers_sent()){
            header('Content-Type: text/html; charset=iso-8859-1');
        }
        $template = $this->app_path . 'views' . DIRSEP . $template ;

        $data['PATH_TPL']=$this->app_dir_name;
        $template = new Template($template);

        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        /****************ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = $this->searchFileRecurse(PATH_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        if($langfile == ''){
            $langfile = $this->searchFileRecurse(PATH_CORE_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        }
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/

        if(isset($_REQUEST['window_id'])){
            $data['window_id']="$('".$_REQUEST['window_id']."')";
        }


        $template->replace_tags($data);
        if ( is_file($langfile) ){
            $template->replace_lang($_SESSION['lang']);
        }
        $template->replace_tags($_SESSION);

        /****************ARCHIVO DE LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = PATH_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL#######################*/
         

        /****************ARCHIVO DE LENGUAJE GLOBAL CARPETA CORE***********************/
        $lang=array();
        $langfile = PATH_CORE_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL CARPETA CORE#######################*/

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/


        $template->output();
    }

    function renderTemplateExternal($template,$data=array(),$writeheader=true){

        $template =  $template ;
        if($writeheader && !headers_sent()){
            header('Content-Type: text/html; charset=iso-8859-1');
        }

        $data['PATH_TPL']=$this->app_dir_name;
        $template = new Template($template);

        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        /****************ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = $this->searchFileRecurse(PATH_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        if($langfile == ''){
            $langfile = $this->searchFileRecurse(PATH_CORE_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        }
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/

        if(isset($_REQUEST['window_id'])){
            $data['window_id']="$('".$_REQUEST['window_id']."')";
        }


        $template->replace_tags($data);
        if ( is_file($langfile) ){
            $template->replace_lang($_SESSION['lang']);
        }
        $template->replace_tags($_SESSION);

        /****************ARCHIVO DE LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = PATH_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL#######################*/


        $template->output();
    }

    function getTemplate($template,$data=array(),$writeheader=true){
        if($writeheader && !headers_sent()){
            header('Content-Type: text/html; charset=ISO-8859-1');
            header ('Expires: Thu, 27 Mar 1980 23:59:00 GMT'); //la pagina expira en una fecha pasada
            header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); //ultima actualizacion ahora cuando la cargamos
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header ('Pragma: no-cache');
        }

        $template = $this->app_path . 'views' . DIRSEP . $template ;
        $template = new Template($template);


        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        /****************ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = $this->searchFileRecurse(PATH_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        if($langfile == ''){
            $langfile = $this->searchFileRecurse(PATH_CORE_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        }
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/

        if(isset($_REQUEST['window_id'])){
            $data['window_id']="$('".$_REQUEST['window_id']."')";
        }


        $template->replace_tags($data);
        if ( is_file($langfile) ){
            $template->replace_lang($_SESSION['lang']);
        }
        $template->replace_tags($_SESSION);

        /****************ARCHIVO DE LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = PATH_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL#######################*/

        return $template->get();
    }

    function renderText($template,$data=array(),$writeheader=true){
        if($writeheader && !headers_sent()){
            header('Content-Type: text/html; charset=iso-8859-1');
        }       

        $data['PATH_TPL']=$this->app_dir_name;
        $template = new Template($template);

        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        /****************ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = $this->searchFileRecurse(PATH_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        if($langfile == ''){
            $langfile = $this->searchFileRecurse(PATH_CORE_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        }
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/

        if(isset($_REQUEST['window_id'])){
            $data['window_id']="$('".$_REQUEST['window_id']."')";
        }


        $template->replace_tags($data);
        if ( is_file($langfile) ){
            $template->replace_lang($_SESSION['lang']);
        }
        $template->replace_tags($_SESSION);

        /****************ARCHIVO DE LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = PATH_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL#######################*/

        $template->output();
    }

    function getText($template,$data=array(),$writeheader=true){
        if($writeheader && !headers_sent()){
            header('Content-Type: text/html; charset=iso-8859-1');
        }       

        $data['PATH_TPL']=$this->app_dir_name;
        $template = new Template($template);

        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        /****************ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = $this->searchFileRecurse(PATH_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        if($langfile == ''){
            $langfile = $this->searchFileRecurse(PATH_CORE_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        }
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/

        if(isset($_REQUEST['window_id'])){
            $data['window_id']="$('".$_REQUEST['window_id']."')";
        }


        $template->replace_tags($data);
        if ( is_file($langfile) ){
            $template->replace_lang($_SESSION['lang']);
        }
        $template->replace_tags($_SESSION);

        /****************ARCHIVO DE LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = PATH_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL#######################*/


        return $template->get();
    }
    
    function renderReport($uuid='', $uuid_modulo='', $_rpt=array()){
        $this->loadController('DeliriumDesigner');
        $rpt = new Controller_DeliriumDesigner();
        $rpt->show($uuid, $uuid_modulo, $_rpt);
    }

    function renderPdf($template,$data=array(),$pdf_filename='',$writeheader=false){
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        ini_set('memory_limit', '850M');
        ini_set('safe_mode',1);
        if(!class_exists('DOMPDF')){
            include(PATH_EXTRA.'dompdf/dompdf_config.inc.php');
        }

        $template = $this->app_path . 'views' . DIRSEP . $template ;
        $template = new Template($template);

        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        /****************ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = $this->searchFileRecurse(PATH_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        if($langfile == ''){
            $langfile = $this->searchFileRecurse(PATH_CORE_APPS,$this->app_name.'.lang.'.$_SESSION['lang'].'.php');
        }
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }

        /*###############ARCHIVO DE LENGUAJE CENTRALIZADO LENGUAJE GLOBAL#######################*/

        if(isset($_REQUEST['window_id'])){
            $data['window_id']="$('".$_REQUEST['window_id']."')";
        }


        $template->replace_tags($data);
        if ( is_file($langfile) ){
            $template->replace_lang($_SESSION['lang']);
        }
        $template->replace_tags($_SESSION);

        /****************ARCHIVO DE LENGUAJE GLOBAL***********************/
        $lang=array();
        $langfile = PATH_APPS.'/langs/lang.'.$_SESSION['lang'].'.php' ;
        if ( is_file($langfile) ){
            include($langfile);
            $template->lang=$lang;
            $template->replace_lang($_SESSION['lang']);
        }
        /*###############ARCHIVO DE LENGUAJE GLOBAL#######################*/

        $html= $template->get();

        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream($pdf_filename.'.pdf');

        error_reporting(E_ALL);
    }

    function index(){}

    /**
     * renderScript  creada por hector para Tuesa
     */
    function renderScript($template,$data=array(),$writeheader=true){
        if($writeheader && !headers_sent()){
            header('Content-Type: text/html; charset=iso-8859-1');
        }
        $template = $this->app_path . 'views' . DIRSEP . $template ;

        $data['PATH_TPL']=$this->app_dir_name;
        //$template = new Script($template);

        if(!isset($_SESSION['lang'])){
            $_SESSION['lang']='es';
        }

        include($template);
    }


    /**
     * push_event 
     * Envia notificacion de un evento a las url establecida
     * @param mixed $url  url del archivo que recive la informacion
     * @param mixed $evt nombre del evento 
     * @param mixed $params array que contiene la informacion que se enviara
     * @access public
     * @return void
     */
    function push_event($url, $evt, $params){
        $params['EVENTO'] = $evt;
        // create curl resource 
        $ch = curl_init(); 
        // set url 
        $site="$url";
        curl_setopt($ch, CURLOPT_URL, $site); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // $output contains the output string 
        $output = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch);      

        return $output;

    
    }
}
?>
