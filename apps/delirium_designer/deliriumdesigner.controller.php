<?php
if(!class_exists('Controller_DeliriumDesigner')){
    class Controller_DeliriumDesigner extends Controller_Base{
        var $opts = array();

        function index(){
            $rpt = array();
            $rpt['uuid'] = 'rpt';
            $rpt['uuid_modulo'] = 'rpt-modulo';
            $rpt['chr_titulo'] = 'rpt-titulo';
            $rpt['top_margin'] = "0";
            $rpt['right_margin'] = "0";
            $rpt['bottom_margin'] = "0";
            $rpt['left_margin'] = "0";
            $rpt['orientation'] = 'P';
            $rpt['width'] = '210';
            $rpt['height'] = '297';
            $rpt['header_height'] = '50';
            $rpt['content_height'] = '50';
            $rpt['footer_height'] = '50';
            $rpt['content_alias'] = '';

            $rpt['lista_objetos_header'] = array();
            $rpt['lista_objetos_content'] = array();
            $rpt['lista_objetos_footer'] = array();
            $rpt['lista_params_config'] = array();
            $rpt['lista_propiedades_groups'] = array();
            $rpt['lista_objetos_groups'] = array();
            $rpt['lista_consultas'] = array();

            $var_fix['{'] ="#|";
            $var_fix['}'] ="|#";

            if(isset($_POST['uuid'])){
                if($_POST['uuid'] != ""){
                    $this->setModel('DeliriumReport');
                    $rpt = $this->model->rpt_metadata($_POST['uuid'],$_POST['uuid_modulo']);

                    $rpt['lista_objetos_header'] = array();
                    $rpt['lista_objetos_content'] = array();
                    $rpt['lista_objetos_footer'] = array();
                    $rpt['lista_params_config'] = array();
                    $rpt['lista_propiedades_groups'] = array();
                    $rpt['lista_objetos_groups'] = array();
                    $rpt['lista_consultas'] = array();

                    foreach($rpt['consultas'] as $consulta){
                        //$rpt['lista_consultas'][] = array('alias' => $consulta['alias'], 'sql' => $consulta['str_sql']);
                        $rpt['lista_consultas'][] = array('alias' => $consulta['alias'], 'sql' => str_replace("%nr%", "%nr%\"+ \"" ,str_replace(array("\r\n", "\n", "\r"), "%nr%", $consulta['str_sql'])));
                    }

                    $objetos = isset($rpt['config']) ? $rpt['config'] : array();
                    foreach($objetos as $objeto){ 

                        $objeto_indice = array();
                        foreach($objeto as $prop => $val){
                            $objeto_indice['param'] = $prop;
                            $objeto_indice[$prop] = strtr($val,$var_fix);
                            $objeto_indice['value'] = $objeto_indice[$prop];
                        }

                        $rpt['lista_params_config'][] = $objeto_indice;
                    }

                    //Objetos del encabezado
                    $objetos = isset($rpt['_header_']) ? $rpt['_header_'] : array();
                    foreach($objetos as $objeto){ 

                        $objeto_indice = array();
                        foreach($objeto as $prop => $val){
                            $objeto_indice[$prop] = strtr($val,$var_fix);
                        }
                        if( !isset($objeto_indice['align']) ){
                            $objeto_indice['align'] = 'L';
                        }
                        $rpt['lista_objetos_header'][] = $objeto_indice;
                    }

                    //Propiedades de las agrupaciones
                    $objetos = isset($rpt['_groups_params_']) ? $rpt['_groups_params_'] : array();
                    foreach($objetos as $objeto){ 

                        $objeto_indice = array();
                        $objeto_indice['group'] = $objeto['seccion'];
                        foreach($objeto as $prop => $val){
                            $objeto_indice[$prop] = strtr($val,$var_fix);
                            $objeto_indice['value'] = $objeto_indice[$prop];
                        }

                        if( !isset($objeto_indice['align']) ){
                            $objeto_indice['align'] = 'L';
                        }

                        $rpt['lista_propiedades_groups'][] = $objeto_indice;
                    }

                    //Objetos de las agrupaciones
                    $objetos = isset($rpt['_groups_']) ? $rpt['_groups_'] : array();
                    foreach($objetos as $objeto){ 

                        $objeto_indice = array();
                        $objeto_indice['group'] = $objeto['seccion'];
                        foreach($objeto as $prop => $val){
                            $objeto_indice[$prop] = strtr($val,$var_fix);
                        }

                        if( !isset($objeto_indice['align']) ){
                            $objeto_indice['align'] = 'L';
                        }

                        $rpt['lista_objetos_groups'][] = $objeto_indice;
                    }

                    //Objetos de detalle
                    $objetos = isset($rpt['_content_']) ? $rpt['_content_'] : array();
                    foreach($objetos as $objeto){ 

                        $objeto_indice = array();
                        foreach($objeto as $prop => $val){
                            $objeto_indice[$prop] = strtr($val,$var_fix);
                        }

                        if( !isset($objeto_indice['align']) ){
                            $objeto_indice['align'] = 'L';
                        }

                        $rpt['lista_objetos_content'][] = $objeto_indice;
                    }


                    $objetos = isset($rpt['_footer_']) ? $rpt['_footer_'] : array();
                    foreach($objetos as $objeto){ 

                        $objeto_indice = array();
                        foreach($objeto as $prop => $val){
                            $objeto_indice[$prop] = strtr($val,$var_fix);
                        }

                        if( !isset($objeto_indice['align']) ){
                            $objeto_indice['align'] = 'L';
                        }

                        $rpt['lista_objetos_footer'][] = $objeto_indice;
                    }
                }
            }

            $this->renderTemplate("workspace_designer.tpl", $rpt);
        }

        function listado(){
            $this->renderTemplate("list.tpl");
        }

        function view_consultas_admin(){
            $lista_alias = explode(",", $_POST['lista_alias']);
            $template['lista_alias'] = array();
            $total_lista_alias = sizeof($lista_alias);
            for ( $x=0; $x < $total_lista_alias; $x++ ){ 
                $template['lista_alias'][] = array("alias" => $lista_alias[$x]);
            }


            $this->renderTemplate('view_consultas_admin.tpl', $template);

        }

        function get_fields(){
            //echo str_replace("%nr%", "\n\r" ,$_POST['sql']);
            $sql = str_replace("%nr%", "\n\r" ,$_POST['sql']);
            $fields = query2vars($sql);
            //$fields = query2vars($this->getText(str_replace("%nr%", "\n\r" ,$_POST['sql'])));
            $ret_fields = '';
            foreach($fields as $key => $value){
                if($ret_fields != ''){
                    $ret_fields .= ',';
                }
                $ret_fields .= $key;
            }
            $this->renderText($ret_fields);
        }

        function test(){
            $txt = " func({dto.ftox},'12.00', {aliasx.campoe})";
            echo $txt;

            preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$return);	
            debug_array($return);
            if(sizeof($return)>0){
                $total_campos = sizeof($return[0]);
                for( $x = 0; $x< $total_campos;$x++ ){
                    $alias = $return['alias'][$x];
                    $campo = $return['campo'][$x];
                    $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$result['$alias'][0]['$campo']", $txt);
                
                }
            }
            echo $txt;

            //$this->template = eregi_replace("{" . $tag . "}", $data,$this->template);   			   	

            //$this->renderTemplate("testUnit.tpl");
        }

        function testUnit(){
            $this->setModel('DeliriumReport');

            $rpt['document']['orientation']="P";
            $rpt['document']['format']=array("214","117");

            $rpt['header']['titulo']['name']="titulo";
            $rpt['header']['titulo']['type']="lbl";
            $rpt['header']['titulo']['txt']="Prueba de titulo";
            $rpt['header']['titulo']['width']="";
            $rpt['header']['titulo']['height']="9";
            $rpt['header']['titulo']['border']="0";
            $rpt['header']['titulo']['align']="C";
            $rpt['header']['titulo']['fill']="1";
            $rpt['header']['titulo']['font-family']='Arial';
            $rpt['header']['titulo']['font-weight']='B';
            $rpt['header']['titulo']['font-size']=15;
            $rpt['header']['titulo']['x']=0;
            $rpt['header']['titulo']['y']=0;

            $rpt['header']['saludo']['name']="saludo";
            $rpt['header']['saludo']['type']="lbl";
            $rpt['header']['saludo']['txt']="saludito";
            $rpt['header']['saludo']['width']="";
            $rpt['header']['saludo']['height']="9";
            $rpt['header']['saludo']['border']="0";
            $rpt['header']['saludo']['align']="C";
            $rpt['header']['saludo']['fill']="1";
            $rpt['header']['saludo']['font-family']='Arial';
            $rpt['header']['saludo']['font-weight']='B';
            $rpt['header']['saludo']['font-size']=15;
            $rpt['header']['saludo']['x']=50;
            $rpt['header']['saludo']['y']=0;

            $rpt['header']['fecha']['name']="fecha";
            $rpt['header']['fecha']['type']="lbl";
            $rpt['header']['fecha']['txt']=date("d-m-Y");
            $rpt['header']['fecha']['height']="9";
            $rpt['header']['fecha']['border']="1";
            $rpt['header']['fecha']['align']="R";
            $rpt['header']['fecha']['fill']="1";
            $rpt['header']['fecha']['font-family']='Arial';
            $rpt['header']['fecha']['font-weight']='B';
            $rpt['header']['fecha']['font-size']=10;
            $rpt['header']['fecha']['font-color-R']=220;
            $rpt['header']['fecha']['font-color-G']=50;
            $rpt['header']['fecha']['font-color-B']=50;
            $rpt['header']['fecha']['font-bgcolor-R']=230;
            $rpt['header']['fecha']['font-bgcolor-G']=230;
            $rpt['header']['fecha']['font-bgcolor-B']=0;
            $rpt['header']['fecha']['x']=100;
            $rpt['header']['fecha']['y']=0;

            $this->model->Render($rpt);
        }

        function phpinfo(){
            phpinfo();
        }

        function header_group($idx = 1, $total, &$rpt, $resultset, &$start_y, &$z=1, $header_height=0, &$j=0){
            //Si existe el grupo
            if( isset($this->opts['seccion']['header-group-'.$idx]) ){
                $group = $this->opts['seccion']['header-group-'.$idx];
                $objetos = isset($this->opts['header-group-'.$idx]) ? $this->opts['header-group-'.$idx] : array();

                //Aplicar el campo group_by al query
                list($alias, $campo) = explode(".", $group['group_by']);
                $group_result = $this->opts['group_'.$idx] =  array_group($resultset, $campo);

                foreach( $group_result as $item=>$value ){
                    //echo "Inicia header $item <br/>";
                    $result = $group_result[$item]['items'];
                    //Escribir los objetos del grupo encabezado
                    $total_objetos = sizeof($objetos);
                    for ( $x = 0 ; $x < $total_objetos; $x++ ){ 
                        //Obtener el objeto
                        $objeto = $this->opts[$objetos[$x]];
                        $objeto_indice = $objeto['id']."_".$j;
                        $seccion = 'content';//$objeto['seccion'];

                        //Inicializar los propiedades del objeto
                        $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                        $rpt[$seccion][$objeto_indice]['type']="lbl2";

                        //Recorrer cada propiedad del objeto
                        foreach($objeto as $prop => $val){
                            //Si se esta guardando el documento guardamos el texto crudo para
                            //respetar las variables
                            if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                $rpt[$seccion][$objeto_indice][$prop] = $val;
                            }else{
                                //Si se muestra un preview renderizamos el texto para mostrar
                                //los valores de las variables
                                $rpt[$seccion][$objeto_indice][$prop] = $val;
                                //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                            }
                        }

                        //Posicionar el objeto en el eje Y
                        $rpt[$seccion][$objeto_indice]['y_old'] = $rpt[$seccion][$objeto_indice]['y'];
                        $rpt[$seccion][$objeto_indice]['y'] += $start_y;

                        switch($objeto['tipo']){
                            case "barcode":
                                if( isset($objeto['field']) && $objeto['field']!='' ){
                                    list($alias, $campo) = explode(".", $objeto['field']);
                                    $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                                    $rpt[$seccion][$objeto_indice]['txt'] = $result[0][$campo];
                                    $rpt[$seccion][$objeto_indice]['code'] = $result[0][$campo];
                                }
                                break;

                            case "field":
                                if( isset($objeto['field']) && $objeto['field']!='' ){
                                    list($alias, $campo) = explode(".", $objeto['field']);
                                    $rpt[$seccion][$objeto_indice]['txt'] = $result[0][$campo];
                                }
                                break;

                            case "fx":
                                if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                }else{
                                    ob_start();

                                    $txt = $rpt[$seccion][$objeto_indice]['txt'];

                                    preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                                    if(sizeof($alias_campo)>0){
                                        $total_campos = sizeof($alias_campo[0]);

                                        for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                            $alias = $alias_campo['alias'][$x_campos];
                                            $campo = $alias_campo['campo'][$x_campos];
                                            $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$result[0]['$campo']", $txt);
                                        }
                                    }


                                    $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                                    $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                                    echo eval($val);
                                    $return=ob_get_contents();
                                    ob_end_clean(); 

                                    $rpt[$seccion][$objeto_indice]['txt'] = $return;
                                }
                                break;
                        }

                        //echo "SY: ".number_format($start_y,0)." + ".number_format($rpt[$seccion][$objeto_indice]['y_old'],0)." = <b>".number_format($rpt[$seccion][$objeto_indice]['y'],0).'</b> <b><i>'.$rpt[$seccion][$objeto_indice]['txt']."</i></b><br />";
                        $j++;
                    }
                    //Incrementar la posicion inicial del objeto
                    $start_y += $group['height'];
                    //Validar si ya no hay espacio en la actual hoja
                    //para impimir se agrega una nueva pagina
                    if($start_y + $group['height']+$header_height >= $this->opts['rpt_props']['height']){
                        $z++;
                        $start_y = $header_height;
                        //echo "nueva pagina $z<br />";
                        $rpt['content']['pagina_'.$z]['type']="page";
                    }

                    if($idx < $total){
                        $this->header_group(($idx +1), $total, $rpt, $result , $start_y, $z, $header_height, $j);
                    } 
                    //echo "fin header $item <br/>";
                    if( $idx == $total ){
                        $start_y_details = $start_y;
                        //echo "inicia contenido $item <br/>";
                        //$last_y = $start_y;
                        $content = isset($this->opts['content']) ? $this->opts['content'] : array();
                        //debug_array($content);
                        //contenido
                        $total_resultados = sizeof($result); 
                        for ( $m=0; $m < $total_resultados ; $m++ ){ 
                            $j++;
                            $total_objetos_content = sizeof($content); 
                            for ( $x = 0 ; $x < $total_objetos_content; $x++ ){ 
                                //Obtener el objeto
                                $objeto = $this->opts[$content[$x]];
                                $objeto_indice = $objeto['id']."_".$j;
                                $seccion = 'content';//$objeto['seccion'];

                                //Inicializar los propiedades del objeto
                                $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                                $rpt[$seccion][$objeto_indice]['type']="lbl2";

                                //Recorrer cada propiedad del objeto
                                foreach($objeto as $prop => $val){
                                    //Si se esta guardando el documento guardamos el texto crudo para
                                    //respetar las variables
                                    if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                        $rpt[$seccion][$objeto_indice][$prop] = $val;
                                    }else{
                                        //Si se muestra un preview renderizamos el texto para mostrar
                                        //los valores de las variables
                                        $rpt[$seccion][$objeto_indice][$prop] = $val;
                                        //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                                    }
                                }

                                //Posicionar el objeto en el eje Y
                                $rpt[$seccion][$objeto_indice]['y_old'] = $rpt[$seccion][$objeto_indice]['y'];
                                $rpt[$seccion][$objeto_indice]['y'] += $start_y;

                                switch($objeto['tipo']){
                                    case "barcode":
                                        if( isset($objeto['field']) && $objeto['field']!='' ){
                                            list($alias, $campo) = explode(".", $objeto['field']);
                                            $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                                            $rpt[$seccion][$objeto_indice]['txt'] = $result[$m][$campo];
                                            $rpt[$seccion][$objeto_indice]['code'] = $result[$m][$campo];
                                        }
                                        break;

                                    case "field":
                                        if( isset($objeto['field']) && $objeto['field']!='' ){
                                            list($alias, $campo) = explode(".", $objeto['field']);
                                            $rpt[$seccion][$objeto_indice]['txt'] = $result[$m][$campo];
                                        }
                                        break;

                                    case "fx":
                                        if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                        }else{
                                            ob_start();

                                            $txt = $rpt[$seccion][$objeto_indice]['txt'];

                                            preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                                            if(sizeof($alias_campo)>0){
                                                $total_campos = sizeof($alias_campo[0]);

                                                for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                                    $alias = $alias_campo['alias'][$x_campos];
                                                    $campo = $alias_campo['campo'][$x_campos];
                                                    $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$result[$m]['$campo']", $txt);
                                                }
                                            }


                                            $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                                            $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                                            echo eval($val);
                                            $return=ob_get_contents();
                                            ob_end_clean(); 

                                            $rpt[$seccion][$objeto_indice]['txt'] = $return;
                                        }
                                        break;
                                }


                                //echo " $item SY: ".number_format($start_y,0)." + ".number_format($rpt[$seccion][$objeto_indice]['y_old'],0)." = <b>".number_format($rpt[$seccion][$objeto_indice]['y'],0).'</b> '.$rpt[$seccion][$objeto_indice]['txt']."<br />";
                            }

                            //Incrementar la posicion inicial del objeto
                            $start_y += $this->opts['content_height'];
                            //echo "[[ $start_y ]]";


                            //Validar si ya no hay espacio en la actual hoja
                            //para impimir se agrega una nueva pagina
                            if($start_y + $this->opts['content_height']+$header_height >= $this->opts['rpt_props']['height']){
                                $z++;
                                $start_y = $this->opts['header_height'];
                                $rpt['content']['pagina_'.$z]['type']="page";
                                //echo "nueva_pagina $z<br/>";
                            }
                        }
                        //echo "fin contenido $item  ".($start_y + $this->opts['content_height'])."<br/>";
                        $this->footer_group($idx, $total, $rpt, $result , $start_y, $z, $header_height, $j, $item, $start_y_details);
                    }

                }
                //debug_array($this->opts['seccion']);


                //FIN GRUPO
            }
        }

        function footer_group($idx = 1, $total, &$rpt, $resultset, &$start_y, &$z=1, $header_height=0, &$j=0, $item, $start_y_details = 0 ){
            //Si existe el grupo
            if( isset($this->opts['seccion']['footer-group-'.$idx]) ){
                //echo "inicia footer $item $start_y <br/>";
                $group = $this->opts['seccion']['footer-group-'.$idx];
                $objetos = isset($this->opts['footer-group-'.$idx]) ? $this->opts['footer-group-'.$idx] : array();

                //Aplicar el campo group_by al query
                //list($alias, $campo) = explode(".", $this->opts['seccion']['header-group-'.$idx]['group_by']);//$group['group_by']);
                $print_from = 'end';

                if( isset($this->opts['seccion']['footer-group-'.$idx]['print_from']) ){
                    $print_from = $this->opts['seccion']['footer-group-'.$idx]['print_from'];
                }
                
                $custom_top_start = 0;
                if( isset($this->opts['seccion']['footer-group-'.$idx]['custom_top_start']) ){
                    $custom_top_start = $this->opts['seccion']['footer-group-'.$idx]['custom_top_start'];
                }

                //$group_result = array_group($resultset, $campo);
                $group_result = $this->opts['group_'.$idx];

                //foreach( $group_result as $item=>$value ){
                    //echo "-ITEM $item <br />";

                    $result = $group_result[$item]['items'];
                    //Escribir los objetos del grupo encabezado
                    $total_objetos = sizeof($objetos);
                    for ( $x = 0 ; $x < $total_objetos; $x++ ){ 
                        //Obtener el objeto
                        $objeto = $this->opts[$objetos[$x]];
                        $objeto_indice = $objeto['id']."_".$j;
                        $seccion = 'content';//$objeto['seccion'];

                        //Inicializar los propiedades del objeto
                        $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                        $rpt[$seccion][$objeto_indice]['type']="lbl2";

                        //Recorrer cada propiedad del objeto
                        foreach($objeto as $prop => $val){
                            //Si se esta guardando el documento guardamos el texto crudo para
                            //respetar las variables
                            if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                $rpt[$seccion][$objeto_indice][$prop] = $val;
                            }else{
                                //Si se muestra un preview renderizamos el texto para mostrar
                                //los valores de las variables
                                $rpt[$seccion][$objeto_indice][$prop] = $val;
                                //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                            }
                        }

                        //Posicionar el objeto en el eje Y
                        if( $print_from == 'end' ){
                            $rpt[$seccion][$objeto_indice]['y'] += $start_y;
                        }else{
                            $rpt[$seccion][$objeto_indice]['y'] += $custom_top_start;
                        }
                        if( $rpt[$seccion][$objeto_indice]['y'] == 0 ){
                            $rpt[$seccion][$objeto_indice]['y'] =0.01;
                        }

                        switch($objeto['tipo']){
                            case "barcode":
                                if( isset($objeto['field']) && $objeto['field']!='' ){
                                    list($alias, $campo) = explode(".", $objeto['field']);
                                    $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                                    $rpt[$seccion][$objeto_indice]['txt'] = $result[0][$campo];
                                    $rpt[$seccion][$objeto_indice]['code'] = $result[0][$campo];
                                }
                                break;

                            case "field":
                                if( isset($objeto['field']) && $objeto['field']!='' ){
                                    list($alias, $campo) = explode(".", $objeto['field']);
                                    $rpt[$seccion][$objeto_indice]['txt'] = $result[0][$campo];
                                }
                                break;

                            case "fx":
                                if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                }else{
                                    ob_start();

                                    $txt = $rpt[$seccion][$objeto_indice]['txt'];

                                    preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                                    if(sizeof($alias_campo)>0){
                                        $total_campos = sizeof($alias_campo[0]);

                                        for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                            $alias = $alias_campo['alias'][$x_campos];
                                            $campo = $alias_campo['campo'][$x_campos];
                                            $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$result[0]['$campo']", $txt);
                                        }
                                    }


                                    $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                                    $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                                    echo eval($val);
                                    $return=ob_get_contents();
                                    ob_end_clean(); 

                                    $rpt[$seccion][$objeto_indice]['txt'] = $return;
                                }
                                break;
                        }

                        //echo "SY: ".number_format($start_y,0)." + "." = <b>".number_format($rpt[$seccion][$objeto_indice]['y'],0).'</b> <b><i>'.$rpt[$seccion][$objeto_indice]['txt']."</i></b><br />";
                        $j++;
                    }
                    //Incrementar la posicion inicial del objeto
                    $start_y += $group['height'];
                    //Validar si ya no hay espacio en la actual hoja
                    //para impimir se agrega una nueva pagina
                    if($start_y + $group['height']+$header_height >= $this->opts['rpt_props']['height']){
                        $z++;
                        $start_y = $header_height;
                        //echo "nueva pagina $z<br />";
                        $rpt['content']['pagina_'.$z]['type']="page";
                    }

                //}
                if($idx > 1){
                    //echo "otro footer <br/>";
                    //$this->footer_group(($idx -1), $total, $rpt, $result , $start_y, $z, $header_height, $j);
                }else{ 
                    //echo "fin footer $item <br/> <br />";
                }


                //FIN GRUPO
            }
        }

        function preview(){
            set_time_limit(60);
            ini_set('memory_limit', '128M');
            $this->opts = $_POST;
            $this->setModel('DeliriumReport');
            $rpt['document']['orientation']=$this->opts['rpt_props']['orientation'];
            $rpt['document']['format']=array($this->opts['rpt_props']['width'],$this->opts['rpt_props']['height']);
            foreach($this->opts['rpt_props'] as $prop => $value){
                $rpt['document'][$prop] = $this->opts['rpt_props'][$prop];
            }
            $rpt['document']['footer_height'] = $this->opts['footer_height'];

            $rpt['content']['pagina_1']['type']="page";

            $consultas = isset($this->opts['sql']) ? $this->opts['sql'] : array();

            $resultsets = array();
            $sql = array();
            $total_consultas = sizeof($consultas);
            for ( $x = 0; $x < $total_consultas; $x++ ){ 
                $alias = $this->opts[$consultas[$x]];
                $alias_indice = $consultas[$x];

                $sql[$alias_indice] = $alias['sql'];

                $resultsets[$alias_indice] = array();
                $resultsets[$alias_indice] = query2array($alias['sql']);
                $rpt['consultas'][] = array('alias' => $alias_indice,'sql' => $alias['sql']);
                //$rpt['consultas'][] = array('alias' => $alias_indice,'sql' => $this->getText($alias['sql']));
            }

            //ENCABEZADO DE PAGINA
            $objetos = isset($this->opts['header']) ? $this->opts['header'] : array();
            $total_objetos_header = sizeof($objetos); 
            for ( $x = 0 ; $x < $total_objetos_header; $x++ ){ 
                $objeto = $this->opts[$objetos[$x]];
                $objeto_indice = $objeto['id'];
                $seccion = $objeto['seccion'];

                $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                $rpt[$seccion][$objeto_indice]['type']='lbl2';
                foreach($objeto as $prop => $val){
                    if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                        $rpt[$seccion][$objeto_indice][$prop] = $val;
                    }else{
                        $rpt[$seccion][$objeto_indice][$prop] = $val;
                        //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                    }
                }

                switch($objeto['tipo']){
                    case "barcode":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                            $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                            $rpt[$seccion][$objeto_indice]['code'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "field":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "fx":
                        if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                        }else{
                            ob_start();

                            $txt = $rpt[$seccion][$objeto_indice]['txt'];

                            preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                            if(sizeof($alias_campo)>0){
                                $total_campos = sizeof($alias_campo[0]);

                                for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                    $alias = $alias_campo['alias'][$x_campos];
                                    $campo = $alias_campo['campo'][$x_campos];
                                    $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$resultsets['$alias'][0]['$campo']", $txt);
                                }
                            }


                            $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                            $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                            echo eval($val);
                            $return=ob_get_contents();
                            ob_end_clean(); 

                            $rpt[$seccion][$objeto_indice]['txt'] = $return;
                        }
                        break;
                }


            }

            //EVALUAR SI EL DOCUMENTO CONTIENE GRUPOS
            if( $rpt['document']['group_count'] > 0 ){
                $group = $this->opts['seccion']['header-group-1'];
                $start_y = $this->opts['header_height'];
                $z = 1;
                if( $this->opts['save'] == 0 ){
                    $this->header_group(1, $rpt['document']['group_count'], $rpt, $resultsets[$group['sql_alias']], $start_y,$z, $this->opts['header_height']);
                }
            }else{
                $objetos = isset($this->opts['content']) ? $this->opts['content'] : array();
                $z=1;
                if($this->opts['content_alias'] != ""){
                    $result = $resultsets[$this->opts['content_alias']];
                    $start_y = $this->opts['header_height'];
                    $total_resultados = sizeof($result);
                    for ( $j=0; $j < $total_resultados; $j++ ){ 
                        $total_objetos = sizeof($objetos);
                        for ( $x = 0 ; $x < $total_objetos; $x++ ){ 
                            $objeto = $this->opts[$objetos[$x]];
                            $objeto_indice = $objeto['id']."_".$j;
                            $seccion = $objeto['seccion'];

                            $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                            $rpt[$seccion][$objeto_indice]['type']="lbl2";
                            foreach($objeto as $prop => $val){
                                if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                    $rpt[$seccion][$objeto_indice][$prop] = $val;
                                }else{
                                    $rpt[$seccion][$objeto_indice][$prop] = $val;
                                    //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                                }
                            }

                            $rpt[$seccion][$objeto_indice]['y'] += $start_y;

                            switch($objeto['tipo']){
                                case "barcode":
                                    if( isset($objeto['field']) && $objeto['field']!='' ){
                                        list($alias, $campo) = explode(".", $objeto['field']);
                                        $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                                        $rpt[$seccion][$objeto_indice]['txt'] = $result[$j][$campo];
                                        $rpt[$seccion][$objeto_indice]['code'] = $result[$j][$campo];
                                    }
                                    break;

                                case "field":
                                    if( isset($objeto['field']) && $objeto['field']!='' ){
                                        list($alias, $campo) = explode(".", $objeto['field']);
                                        $rpt[$seccion][$objeto_indice]['txt'] = $result[$j][$campo];
                                    }
                                    break;

                                case "fx":
                                    if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                    }else{
                                        ob_start();

                                        $txt = $rpt[$seccion][$objeto_indice]['txt'];

                                        preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                                        if(sizeof($alias_campo)>0){
                                            $total_campos = sizeof($alias_campo[0]);

                                            for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                                $alias = $alias_campo['alias'][$x_campos];
                                                $campo = $alias_campo['campo'][$x_campos];
                                                $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$result[$j]['$campo']", $txt);
                                            }
                                        }


                                        $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                                        $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                                        echo eval($val);
                                        $return=ob_get_contents();
                                        ob_end_clean(); 

                                        $rpt[$seccion][$objeto_indice]['txt'] = $return;
                                    }
                                    break;
                            }


                        }
                        $start_y += $this->opts['content_height'];
                        if($start_y + $this->opts['content_height']+$this->opts['header_height'] >= $this->opts['rpt_props']['height']){
                            $z++;
                            $start_y = $this->opts['header_height'];
                            $rpt['content']['pagina_'.$z]['type']="page";
                        }
                    }
                }else{
                    $total_objetos = sizeof($objetos);
                    for ( $x = 0 ; $x < $total_objetos; $x++ ){ 
                        $objeto = $this->opts[$objetos[$x]];
                        $objeto_indice = $objeto['id'];
                        $seccion = $objeto['seccion'];

                        $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                        $rpt[$seccion][$objeto_indice]['type']="lbl2";
                        foreach($objeto as $prop => $val){
                            if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                $rpt[$seccion][$objeto_indice][$prop] = $val;
                            }else{
                                $rpt[$seccion][$objeto_indice][$prop] = $val;
                                //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                            }
                        }

                        $rpt[$seccion][$objeto_indice]['y'] += $this->opts['header_height'];

                        switch($objeto['tipo']){
                            case "barcode":
                                if( isset($objeto['field']) && $objeto['field']!='' ){
                                    list($alias, $campo) = explode(".", $objeto['field']);
                                    $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                                    $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                                    $rpt[$seccion][$objeto_indice]['code'] = $resultsets[$alias][0][$campo];
                                }
                                break;

                            case "field":
                                if( isset($objeto['field']) && $objeto['field']!='' ){
                                    list($alias, $campo) = explode(".", $objeto['field']);
                                    $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                                }
                                break;

                            case "fx":
                                if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                                }else{
                                    ob_start();

                                    $txt = $rpt[$seccion][$objeto_indice]['txt'];

                                    preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                                    if(sizeof($alias_campo)>0){
                                        $total_campos = sizeof($alias_campo[0]);

                                        for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                            $alias = $alias_campo['alias'][$x_campos];
                                            $campo = $alias_campo['campo'][$x_campos];
                                            $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$resultsets['$alias'][0]['$campo']", $txt);
                                        }
                                    }


                                    $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                                    $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                                    echo eval($val);
                                    $return=ob_get_contents();
                                    ob_end_clean(); 

                                    $rpt[$seccion][$objeto_indice]['txt'] = $return;
                                }
                                break;
                        }

                    }
                }
            }

            $objetos = isset($this->opts['footer']) ? $this->opts['footer'] : array();
            $total_objetos_footer = sizeof($objetos);
            for ( $x = 0 ; $x < $total_objetos_footer; $x++ ){ 
                $objeto = $this->opts[$objetos[$x]];
                $objeto_indice = $objeto['id'];
                $seccion = $objeto['seccion'];

                $rpt[$seccion][$objeto_indice]['name']=$objeto['id'];
                $rpt[$seccion][$objeto_indice]['type']="lbl2";
                foreach($objeto as $prop => $val){
                    if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                        $rpt[$seccion][$objeto_indice][$prop] = $val;
                    }else{
                        $rpt[$seccion][$objeto_indice][$prop] = $val;
                        //$rpt[$seccion][$objeto_indice][$prop] = $this->getText($val);
                    }
                }
                
                switch($objeto['tipo']){
                    case "barcode":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                            $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                            $rpt[$seccion][$objeto_indice]['code'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "field":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "fx":
                        if($this->opts['save'] == 1 || $this->opts['save'] == 2){
                        }else{
                            ob_start();

                            $txt = $rpt[$seccion][$objeto_indice]['txt'];

                            preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                            if(sizeof($alias_campo)>0){
                                $total_campos = sizeof($alias_campo[0]);

                                for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                    $alias = $alias_campo['alias'][$x_campos];
                                    $campo = $alias_campo['campo'][$x_campos];
                                    $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$resultsets['$alias'][0]['$campo']", $txt);
                                }
                            }


                            $rpt[$seccion][$objeto_indice]['txt'] = $txt; 

                            $val = 'echo '.$rpt[$seccion][$objeto_indice]['txt'].';';
                            echo eval($val);
                            $return=ob_get_contents();
                            ob_end_clean(); 

                            $rpt[$seccion][$objeto_indice]['txt'] = $return;
                        }
                        break;
                }

            }

            if($this->opts['save'] == 1){
                $rpt['document']['header_height'] = $this->opts['header_height'];
                $rpt['document']['content_height'] = $this->opts['content_height'];
                $rpt['document']['content_alias'] = $this->opts['content_alias'];
                $rpt['document']['footer_height'] = $this->opts['footer_height'];


                if( $rpt['document']['group_count'] > 0 ){
                    $rpt['groups'] = array();
                    for ( $x=1; $x <= $rpt['document']['group_count']; $x++ ){ 
                        $objetos = isset($this->opts['header-group-'.$x]) ? $this->opts['header-group-'.$x] : array();
                        if ( isset($this->opts['seccion']['header-group-'.$x]) ){
                            $rpt['groups']['header-group-'.$x] = array($this->opts['seccion']['header-group-'.$x]);
                        }

                        $total_objetos = sizeof($objetos);
                        for ( $y = 0 ; $y < $total_objetos; $y++ ){ 
                            $objeto = $this->opts[$objetos[$y]];
                            $objeto_indice = $objeto['id'];

                            $rpt['_header-group-'.$x.'_'][$objeto_indice]['name']=$objeto['id'];
                            $rpt['_header-group-'.$x.'_'][$objeto_indice]['type']="lbl2";
                            foreach($objeto as $prop => $val){
                                $rpt['_header-group-'.$x.'_'][$objeto_indice][$prop] = $val;
                            }
                        }

                        $objetos = isset($this->opts['footer-group-'.$x]) ? $this->opts['footer-group-'.$x] : array();
                        if ( isset($this->opts['seccion']['footer-group-'.$x]) ){
                            $rpt['groups']['footer-group-'.$x] = array($this->opts['seccion']['footer-group-'.$x]);
                        }

                        $total_objetos = sizeof($objetos);
                        for ( $y = 0 ; $y < $total_objetos; $y++ ){ 
                            $objeto = $this->opts[$objetos[$y]];
                            $objeto_indice = $objeto['id'];

                            $rpt['_footer-group-'.$x.'_'][$objeto_indice]['name']=$objeto['id'];
                            $rpt['_footer-group-'.$x.'_'][$objeto_indice]['type']="lbl2";
                            foreach($objeto as $prop => $val){
                                $rpt['_footer-group-'.$x.'_'][$objeto_indice][$prop] = $val;
                            }
                        }
                    }
                }

                $objetos = isset($this->opts['content']) ? $this->opts['content'] : array();
                $total_objetos_content = sizeof($objetos);
                for ( $x = 0 ; $x < $total_objetos_content; $x++ ){ 
                    $objeto = $this->opts[$objetos[$x]];
                    $objeto_indice = $objeto['id'];

                    $rpt['_content_'][$objeto_indice]['name']=$objeto['id'];
                    $rpt['_content_'][$objeto_indice]['type']="lbl2";
                    foreach($objeto as $prop => $val){
                        $rpt['_content_'][$objeto_indice][$prop] = $val;
                    }
                }
                $this->model->save($rpt);
                $this->renderText("
                    <script type=\"text/javascript\">
                        window.onload = function(){
                            alert('%DOCUMENTO_GUARDADO%');
                            window.close();
                        }
                    </script>
                    ");
                //$this->model->Render($rpt);
            }else if($this->opts['save'] == 2){
                $rpt['document']['header_height'] = $this->opts['header_height'];
                $rpt['document']['content_height'] = $this->opts['content_height'];
                $rpt['document']['content_alias'] = $this->opts['content_alias'];
                $rpt['document']['footer_height'] = $this->opts['footer_height'];

                if( $rpt['document']['group_count'] > 0 ){
                    $rpt['groups'] = array();
                    for ( $x=1; $x <= $rpt['document']['group_count']; $x++ ){ 
                        $objetos = isset($this->opts['header-group-'.$x]) ? $this->opts['header-group-'.$x] : array();
                        if ( isset($this->opts['seccion']['header-group-'.$x]) ){
                            $rpt['groups']['header-group-'.$x] = array($this->opts['seccion']['header-group-'.$x]);
                        }

                        $total_objetos = sizeof($objetos);
                        for ( $y = 0 ; $y < $total_objetos; $y++ ){ 
                            $objeto = $this->opts[$objetos[$y]];
                            $objeto_indice = $objeto['id'];

                            $rpt['_header-group-'.$x.'_'][$objeto_indice]['name']=$objeto['id'];
                            $rpt['_header-group-'.$x.'_'][$objeto_indice]['type']="lbl2";
                            foreach($objeto as $prop => $val){
                                $rpt['_header-group-'.$x.'_'][$objeto_indice][$prop] = $val;
                            }
                        }

                        $objetos = isset($this->opts['footer-group-'.$x]) ? $this->opts['footer-group-'.$x] : array();
                        if ( isset($this->opts['seccion']['footer-group-'.$x]) ){
                            $rpt['groups']['footer-group-'.$x] = array($this->opts['seccion']['footer-group-'.$x]);
                        }

                        $total_objetos = sizeof($objetos);
                        for ( $y = 0 ; $y < $total_objetos; $y++ ){ 
                            $objeto = $this->opts[$objetos[$y]];
                            $objeto_indice = $objeto['id'];

                            $rpt['_footer-group-'.$x.'_'][$objeto_indice]['name']=$objeto['id'];
                            $rpt['_footer-group-'.$x.'_'][$objeto_indice]['type']="lbl2";
                            foreach($objeto as $prop => $val){
                                $rpt['_footer-group-'.$x.'_'][$objeto_indice][$prop] = $val;
                            }
                        }
                    }
                }

                $objetos = isset($this->opts['content']) ? $this->opts['content'] : array();
                $rpt['_content_'] = array();
                $total_objetos_content = sizeof($objetos);
                for ( $x = 0 ; $x < $total_objetos_content; $x++ ){ 
                    $objeto = $this->opts[$objetos[$x]];
                    $objeto_indice = $objeto['id'];

                    $rpt['_content_'][$objeto_indice]['name']=$objeto['id'];
                    $rpt['_content_'][$objeto_indice]['type']="lbl2";
                    foreach($objeto as $prop => $val){
                        $rpt['_content_'][$objeto_indice][$prop] = $val;
                    }
                }
                header("Content-type: text/plain");
                header("Expires: Thu, 27 Mar 1980 23:59:00 GMT"); //la pagina expira en una fecha pasada
                header("Last-Modified: " . gmdate("D, d  Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
                header("Cache-Control: no-store, no-cache, must-revalidate");
                header("Cache-Control: no-cache, must-revalidate"); 			
                header("Pragma: no-cache");			
                header("Content-Disposition: attachment; filename=".$rpt['document']['uuid'].'-'.$rpt['document']['uuid_modulo'].'.drp');
                echo "<?php\n";
                recursive_print('$rpt', $rpt);
                echo "?>\n";
            }else{
                $this->model->Render($rpt);
                debug_array($rpt);
            }
        }

        function testme(){
            $this->show($_GET['uuid'], $_GET['modulo']);
        }

        function show($uuid='', $uuid_modulo='' ,$_rpt=array()){            
            $this->setModel('DeliriumReport');

            $rpt = $this->model->rpt_metadata($uuid,$uuid_modulo);

            $rpt['document']['format']=array($rpt['width'],$rpt['height']);
            $rpt['document']['footer_height']=$rpt['footer_height'];

            $rpt['document']['group_count'] = $rpt['config']['param']['group_count'];

            $rpt['document']['filename'] = $rpt['chr_titulo'].'.pdf';
            $this->opts = $rpt;


            $rpt['content']['pagina_1']['type']="page";
            $consultas = $rpt['consultas'];

            $resultsets = array();
            foreach($rpt['consultas'] as $consulta){
                $alias_indice = $consulta['alias'];
                if(isset($_rpt['consultas'][$alias_indice])){
                    $consulta['str_sql'] = $_rpt['consultas'][$alias_indice];
                }
                $resultsets[$alias_indice] = array();
                $resultsets[$alias_indice] = query2array($consulta['str_sql']);
                //$resultsets[$alias_indice] = query2array($this->getText($consulta['str_sql']));
            }

            $objetos = $rpt['_header_'];
            foreach($objetos as $objeto){ 
                $objeto_indice = $objeto['id'];

                $rpt['header'][$objeto_indice]['name']=$objeto['id'];
                $rpt['header'][$objeto_indice]['type']="lbl2";
                foreach($objeto as $prop => $val){
                    $rpt['header'][$objeto_indice][$prop] = $val;
                    //$rpt['header'][$objeto_indice][$prop] = $this->getText($val);
                }

                switch($objeto['tipo']){
                    case "barcode":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                            $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                            $rpt[$seccion][$objeto_indice]['code'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "field":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt['header'][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "fx":
                        ob_start();

                        $txt = $rpt['header'][$objeto_indice]['txt'];

                        preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                        if(sizeof($alias_campo)>0){
                            $total_campos = sizeof($alias_campo[0]);

                            for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                $alias = $alias_campo['alias'][$x_campos];
                                $campo = $alias_campo['campo'][$x_campos];
                                $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$resultsets['$alias'][0]['$campo']", $txt);
                            }
                        }


                        $rpt['header'][$objeto_indice]['txt'] = $txt; 

                        $val = 'echo '.$rpt['header'][$objeto_indice]['txt'].';';
                        echo eval($val);
                        $return=ob_get_contents();
                        ob_end_clean(); 

                        $rpt['header'][$objeto_indice]['txt'] = $return;
                        break;
                }


            }

            if( $rpt['document']['group_count'] > 0 ){
                $group = $this->opts['seccion']['header-group-1'];
                $start_y = $this->opts['header_height'];
                $z = 1;
                //debug_array($group);
                $this->header_group(1, $rpt['document']['group_count'], $rpt, $resultsets[$group['sql_alias']], $start_y,$z, $this->opts['header_height']);
            }else{
                $objetos = $rpt['_content_'];
                $z=1;
                if($rpt['content_alias'] != ""){
                    $result = $resultsets[$rpt['content_alias']];
                    $start_y = $rpt['header_height'];
                    $total_resultados = sizeof($result);
                    for ( $j=0; $j < $total_resultados; $j++ ){ 
                        foreach($objetos as $objeto){ 
                            $objeto_indice = $objeto['id']."_".$j;

                            $rpt['content'][$objeto_indice]['name']=$objeto['id'];
                            $rpt['content'][$objeto_indice]['type']="lbl2";
                            foreach($objeto as $prop => $val){
                                $rpt['content'][$objeto_indice][$prop] = $val;
                                //$rpt['content'][$objeto_indice][$prop] = $this->getText($val);
                            }

                            $rpt['content'][$objeto_indice]['y'] += $start_y;

                            switch($objeto['tipo']){
                                case "barcode":
                                    if( isset($objeto['field']) && $objeto['field']!='' ){
                                        list($alias, $campo) = explode(".", $objeto['field']);
                                        $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                                        $rpt[$seccion][$objeto_indice]['txt'] = $result[$j][$campo];
                                        $rpt[$seccion][$objeto_indice]['code'] = $result[$j][$campo];
                                    }
                                    break;

                                case "field":
                                    if( isset($objeto['field']) && $objeto['field']!='' ){
                                        list($alias, $campo) = explode(".", $objeto['field']);
                                        $rpt['content'][$objeto_indice]['txt'] = $result[$j][$campo];
                                    }
                                    break;

                                case "fx":
                                    ob_start();

                                    $txt = $rpt['content'][$objeto_indice]['txt'];

                                    preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                                    if(sizeof($alias_campo)>0){
                                        $total_campos = sizeof($alias_campo[0]);

                                        for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                            $alias = $alias_campo['alias'][$x_campos];
                                            $campo = $alias_campo['campo'][$x_campos];
                                            $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$result[$j]['$campo']", $txt);
                                        }
                                    }


                                    $rpt['content'][$objeto_indice]['txt'] = $txt; 

                                    $val = 'echo '.$rpt['content'][$objeto_indice]['txt'].';';
                                    echo eval($val);
                                    $return=ob_get_contents();
                                    ob_end_clean(); 

                                    $rpt[$seccion][$objeto_indice]['txt'] = $return;
                                    break;
                            }

                        }

                        $start_y += $rpt['content_height'];
                        if($start_y + $rpt['content_height']+$rpt['header_height'] >= $rpt['height']){
                            $z++;
                            $start_y = $rpt['header_height'];
                            $rpt['content']['pagina_'.$z]['type']="page";
                        }
                    }
                }
            }

            $objetos = $rpt['_footer_'];
            foreach($objetos as $objeto){ 
                $objeto_indice = $objeto['id'];

                $rpt['footer'][$objeto_indice]['name']=$objeto['id'];
                $rpt['footer'][$objeto_indice]['type']="lbl2";
                foreach($objeto as $prop => $val){
                    $rpt['footer'][$objeto_indice][$prop] = $val;
                    //$rpt['footer'][$objeto_indice][$prop] = $this->getText($val);
                }

                switch($objeto['tipo']){
                    case "barcode":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt[$seccion][$objeto_indice]['type'] = 'barcode';
                            $rpt[$seccion][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                            $rpt[$seccion][$objeto_indice]['code'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "field":
                        if( isset($objeto['field']) && $objeto['field']!='' ){
                            list($alias, $campo) = explode(".", $objeto['field']);
                            $rpt['footer'][$objeto_indice]['txt'] = $resultsets[$alias][0][$campo];
                        }
                        break;

                    case "fx":
                        ob_start();

                        $txt = $rpt['footer'][$objeto_indice]['txt'];

                        preg_match_all ('/{(?P<alias>\w+)\.(?P<campo>\w+)}/',$txt,$alias_campo);	
                        if(sizeof($alias_campo)>0){
                            $total_campos = sizeof($alias_campo[0]);

                            for( $x_campos = 0; $x_campos< $total_campos;$x_campos++ ){
                                $alias = $alias_campo['alias'][$x_campos];
                                $campo = $alias_campo['campo'][$x_campos];
                                $txt = str_replace('{'.$alias.'.'.$campo.'}', "\$resultsets['$alias'][0]['$campo']", $txt);
                            }
                        }


                        $rpt['footer'][$objeto_indice]['txt'] = $txt; 

                        $val = 'echo '.$rpt['footer'][$objeto_indice]['txt'].';';
                        echo eval($val);
                        $return=ob_get_contents();
                        ob_end_clean(); 

                        $rpt['footer'][$objeto_indice]['txt'] = $return;
                        break;
                }
            }
            $this->model->Render($rpt);

        }

        function remove(){
            $this->setModel('DeliriumReport');
            $this->model->remove($_POST['id_reporte']);
        }

        function view_import(){
            $this->renderTemplate('view_importar.tpl');
        }

        function import(){
            $rpt = array();
            if(sizeof($_FILES)>0){
                $archivo = $_FILES["blb_rpt"]["tmp_name"];
                if ($archivo != "none"&&strlen($archivo)>0) {
                    $this->setModel('DeliriumReport');
                    include($archivo);
                    $this->model->save($rpt);
                }
            }
        }
    }
}
?>
