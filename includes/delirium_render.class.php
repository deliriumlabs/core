<?php 
class Template{
    var $template;
    var $file;
    var $lang;

    function Template($template = '') {
        $this->file=$template;
        if (file_exists($template)){
            $this->template = $this->parse($template);
        }else{
            $this->template = $this->parseText($template);
        }
    }

    function parse($file) {
        clearstatcache();
        ob_start();
        include($file);
        $buffer = ob_get_contents();
        ob_end_clean();
        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
            if (!in_array('ob_gzhandler', ob_list_handlers())) {
                ob_start('ob_gzhandler');
            } 
        }else{
            ob_start();
        } 
        return $buffer;
    }

    function parseText($text) {
        clearstatcache();
        ob_start();
        echo ($text);
        $buffer = ob_get_contents();
        ob_end_clean();
        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
            if (!in_array('ob_gzhandler', ob_list_handlers())) {
                ob_start('ob_gzhandler');
            } 
        }else{
            ob_start();
        }
        return $buffer;
    }

    function replace_lang($languaje_id='es'){
        $lang=$this->lang;
        if ( is_file($this->file.'.lang.php') ){
            include($this->file.'.lang.php');
        }

        if(!isset($lang[$languaje_id])){
            if(!isset($lang['es'])){				
                return false;
            }elseif (!is_array($lang['es'])){
                return false;
            }
            $languaje_id='es';
        }elseif(!is_array($lang[$languaje_id])){
            return false;
        }
        $lang=$lang[$languaje_id];
        if (sizeof($lang) > 0){
            foreach ($lang as $tag => $data) {			    			   	
                $this->template = preg_replace('/%' . $tag . '%/', $data,$this->template);   			   				   	
            }		    
        }else{
            //die("No hay tags que remplazar.");
        }
    }

    function replace_tags($tags = array()) {		
        if (sizeof($tags) > 0){
            foreach ($tags as $tag => $data) {			    
                if(is_resource($data)){     			   					   		
                    $this->replace_block_tags($tag,$data);
                }elseif (is_array($data)) {
                    $this->template=$this->replace_block_tags2array($tag,$data,$this->template);
                }else{
                    $this->template = preg_replace('/{' . $tag . '}/', $data,$this->template);   			   	
                }
            }

        }else{
            //die("No hay tags que remplazar.");
        }
    }

    function get_block($blockname,$seccion){
        preg_match ('#{{START '. $blockname . '}}([\s\S]+?){{END '. $blockname . '}}#',$seccion,$return);	
        if(sizeof($return)>0){
            $code = str_replace('{{START '. $blockname . '}}', '', $return[0]);
            $code = str_replace('{{END '  . $blockname . '}}', '', $code);			
            return array($code,$return[0]);
        }
        return false;
    }

    function replace_block_tags2array($blockname, $array,$seccion) {
        $return_block=$this->get_block($blockname,$seccion);
        while($return_block!=false){
            $blockcode = $return_block[0];
            $return_block=$return_block[1];		
            if(isset($blockcode)){
                $block_page='';					

                $total_items_array = sizeof($array);
                for ( $x=0; $x < $total_items_array; ++$x ) {
                    $block = $blockcode;
                    $tags=$array[$x];
                    foreach ($tags as $tag => $data) {	
                        if (is_array($tags[$tag])) {
                            $block = $this->replace_block_tags2array($tag,$data,$block);		        		 
                        }else{
                            $block = str_replace('[' . $tag . ']', $data, $block);		            	
                        }
                    } 
                    $block_page .= $block ."\n";			 	
                }
                return str_replace($return_block, $block_page, $seccion);
                unset($block_page); 
            }
            $return_block=$this->get_block($blockname,$seccion);
        }
        return $seccion;
    }

    function output() {  	
        //include($this->template);  	
        echo $this->template;
    }

    function get(){
        return $this->template;
    }
}

function str_replace_count($search,$replace,$subject,$times=1) {
    $subject_original=$subject;
    $len=strlen($search);    
    $pos=0;
    for ( $i = 1; $i <= $times; ++$i) {
        $pos=strpos($subject,$search,$pos);
        if($pos!==false) {                
            $subject=substr($subject_original,0,$pos);
            $subject.=$replace;
            $subject.=substr($subject_original,$pos+$len);
            $subject_original=$subject;
        } else {
            break;
        }
    }
    return($subject);
}
?>
