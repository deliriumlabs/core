<?php
if(!class_exists('FPDF')){
    include "extra/fpdf/fpdf.php";
}
if(!class_exists('Model_DeliriumReport')){
    class Model_DeliriumReport extends FPDF {
        var $rpt;

        var $T128;                                             // tableau des codes 128
        var $ABCset="";                                        // jeu des caractères éligibles au C128
        var $Aset="";                                          // Set A du jeu des caractères éligibles
        var $Bset="";                                          // Set B du jeu des caractères éligibles
        var $Cset="";                                          // Set C du jeu des caractères éligibles
        var $SetFrom;                                          // Convertisseur source des jeux vers le tableau
        var $SetTo;                                            // Convertisseur destination des jeux vers le tableau
        var $JStart = array("A"=>103, "B"=>104, "C"=>105);     // Caractères de sélection de jeu au début du C128
        var $JSwap = array("A"=>101, "B"=>100, "C"=>99);       // Caractères de changement de jeu

        function save($rpt){
            //Obtener el nuevo id del documento
            $id_reporte = "SELECT id_reporte FROM designer_tbl_reportes WHERE uuid = '{$rpt['document']['uuid']}'";
            $id_reporte = query2vars($id_reporte);
            $id_reporte = isset($id_reporte['id_reporte']) ? $id_reporte['id_reporte'] : 0;
            //Borrar el reporte actual
            $strSqlClear = "DELETE FROM designer_tbl_reportes WHERE uuid = '{$rpt['document']['uuid']}'";
            query($strSqlClear);

            //Borrar las consultas relacionadas al id del reporte
            $strSqlClear = "DELETE FROM designer_rel_reporte_sql WHERE id_reporte = '$id_reporte'";
            query($strSqlClear);

            //Borrar los objetos relacionados al id del reporte
            $strSqlClear = "DELETE FROM designer_rel_reporte_objeto_propiedades WHERE id_reporte = '$id_reporte'";
            query($strSqlClear);

            //Registrar datos del documento
            $strSqlRpt = "
                INSERT INTO 
                    designer_tbl_reportes 
                    (token, uuid, uuid_modulo, chr_titulo, orientation, width, height, top_margin, right_margin, bottom_margin, left_margin, header_height, content_height, footer_height, content_alias)
                VALUES
                (
                    '{$_SESSION['token']}','{$rpt['document']['uuid']}', '{$rpt['document']['uuid_modulo']}', '{$rpt['document']['chr_titulo']}',
                    '{$rpt['document']['orientation']}', '{$rpt['document']['format'][0]}', '{$rpt['document']['format'][1]}', 
                    {$rpt['document']['top_margin']}, {$rpt['document']['right_margin']}, {$rpt['document']['bottom_margin']}, {$rpt['document']['left_margin']},
                    {$rpt['document']['header_height']},{$rpt['document']['content_height']},{$rpt['document']['footer_height']},'{$rpt['document']['content_alias']}' 
                )
                ";
            query($strSqlRpt);

            //Obtener el nuevo id del documento
            $id_reporte = "SELECT id_reporte FROM designer_tbl_reportes WHERE token = '{$_SESSION['token']}'";
            $id_reporte = query2vars($id_reporte);
            $id_reporte = $id_reporte['id_reporte'];

            //Registrar las consultas
            if( isset($rpt['consultas']) ){
                $total_consultas = sizeof($rpt['consultas']); 
                for ( $x=0; $x < $total_consultas; $x++ ){ 
                    $consulta = $rpt['consultas'][$x];
                    $consulta['sql'] = addslashes($consulta['sql']);
                    $strSqlQueries = "
                        INSERT INTO 
                            designer_rel_reporte_sql 
                                (id_reporte, alias, str_sql) 
                        VALUES
                                ('$id_reporte', '{$consulta['alias']}', '{$consulta['sql']}') 
                        ";
                    query($strSqlQueries);
                }
            }

            //Registrar opciones del reporte
            $rpt['config'] = array();
            $rpt['config']['param'] = array();
            $rpt['config']['param']['group_count'] = isset($rpt['document']['group_count']) ? $rpt['document']['group_count'] : 0 ;

            foreach($rpt['config'] as $objeto){
                foreach($objeto as $prop =>$val){
                    $strSql="
                        INSERT INTO 
                        designer_rel_reporte_objeto_propiedades 
                            (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                        VALUES
                            ('$id_reporte', 'config', 'param', '$prop', '$val') 
                                ";
                    query($strSql);
                    //echo $strSql;
                    //echo "<hr />";
                }
            }

            for ( $x=1; $x <= $rpt['config']['param']['group_count']; $x++ ){ 
                //GROUP HEADER
                if( isset($rpt['groups']['header-group-'.$x]) ){
                    
                    foreach($rpt['groups']['header-group-'.$x] as $objeto){
                        foreach($objeto as $prop =>$val){
                            $strSql="
                                INSERT INTO 
                                designer_rel_reporte_objeto_propiedades 
                                (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                                VALUES
                                ('$id_reporte', 'header-group-{$x}', 'header-group-{$x}', '$prop', '$val') 
                                ";
                            query($strSql);
                            //echo $strSql;
                            //echo "<hr />";
                        }
                    }
                }

                if( isset($rpt['_header-group-'.$x.'_']) ){
                    foreach($rpt['_header-group-'.$x.'_'] as $objeto){
                        foreach($objeto as $prop =>$val){
                            $strSql="
                                INSERT INTO 
                                designer_rel_reporte_objeto_propiedades 
                                (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                                VALUES
                                ('$id_reporte', '_header-group-{$x}_', '{$objeto['name']}', '$prop', '$val') 
                                ";
                            query($strSql);
                            //echo $strSql;
                            //echo "<hr />";
                        }
                    }
                }

                //GROUP FOOTER
                if( isset($rpt['groups']['footer-group-'.$x]) ){
                    
                    foreach($rpt['groups']['footer-group-'.$x] as $objeto){
                        foreach($objeto as $prop =>$val){
                            $strSql="
                                INSERT INTO 
                                designer_rel_reporte_objeto_propiedades 
                                (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                                VALUES
                                ('$id_reporte', 'footer-group-{$x}', 'footer-group-{$x}', '$prop', '$val') 
                                ";
                            query($strSql);
                        }
                    }
                }

                if( isset($rpt['_footer-group-'.$x.'_']) ){
                    foreach($rpt['_footer-group-'.$x.'_'] as $objeto){
                        foreach($objeto as $prop =>$val){
                            $strSql="
                                INSERT INTO 
                                designer_rel_reporte_objeto_propiedades 
                                (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                                VALUES
                                ('$id_reporte', '_footer-group-{$x}_', '{$objeto['name']}', '$prop', '$val') 
                                ";
                            query($strSql);
                        }
                    }
                }
            }

            //Registrar los objetos del header
            if( isset($rpt['header']) ){
                foreach($rpt['header'] as $objeto){
                    foreach($objeto as $prop =>$val){
                        if( $objeto['tipo'] == "field" && $prop == "txt" ){
                            continue;
                        }
                        $val =  addslashes($val);
                        $strSql="
                            INSERT INTO 
                            designer_rel_reporte_objeto_propiedades 
                            (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                            VALUES
                            ('$id_reporte', 'header', '{$objeto['name']}', '$prop', '$val') 
                            ";
                        query($strSql);
                        //echo $strSql;
                        //echo "<hr />";
                    }
                }
            }

            //Registrar los objetos del content
            $rpt['_content_'] = isset($rpt['_content_']) ? $rpt['_content_'] : array();
            foreach($rpt['_content_'] as $objeto){
                foreach($objeto as $prop =>$val){
                    if( $objeto['tipo'] == "field" && $prop == "txt" ){
                        continue;
                    }
                    $strSql="
                        INSERT INTO 
                        designer_rel_reporte_objeto_propiedades 
                            (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                        VALUES
                            ('$id_reporte', 'content', '{$objeto['name']}', '$prop', '$val') 
                                ";
                    query($strSql);
                    //echo $strSql;
                    //echo "<hr />";
                }
            }

            //Registrar los objetos del footer
            if( isset($rpt['footer']) ){
                foreach($rpt['footer'] as $objeto){
                    foreach($objeto as $prop =>$val){
                        if( $objeto['tipo'] == "field" && $prop == "txt" ){
                            continue;
                        }
                        $strSql="
                            INSERT INTO 
                            designer_rel_reporte_objeto_propiedades 
                            (id_reporte, seccion, uuid_objeto, obj_propiedad, value) 
                            VALUES
                            ('$id_reporte', 'footer', '{$objeto['name']}', '$prop', '$val') 
                            ";
                        query($strSql);
                        //echo $strSql;
                        //echo "<hr />";
                    }
                }
            }

        }

        function rpt_metadata($uuid='',$uuid_modulo=''){
            //Obtener el  documento
            $strSqlReporte = "
                SELECT 
                    * 
                FROM 
                    designer_tbl_reportes 
                WHERE 
                    uuid = '$uuid'";
            $reporte = query2vars($strSqlReporte);
            $id_reporte = $reporte['id_reporte'];

            $reporte['opts'] = array();
            $reporte['rpt_props']['height'] = $reporte['height'];
            $reporte['save'] = 0;
            //Obtener las consultas relacionadas al id del reporte
            $strSqlConsultas = "
                SELECT
                    *
                FROM 
                    designer_rel_reporte_sql 
                WHERE 
                    id_reporte = '$id_reporte'";
            $reporte['consultas'] = query2array($strSqlConsultas);

            //Obtener configuraciones del documento
            $strSqlObjetos = "
                SELECT 
                    *
                FROM 
                    designer_rel_reporte_objeto_propiedades 
                WHERE 
                    id_reporte = '$id_reporte'
                AND 
                    seccion ='config'";
            $config = query2array($strSqlObjetos);
            $reporte['config'] = array();
            $total_config = sizeof($config);
            for($x = 0; $x < $total_config; $x++){
                $param = $config[$x];
                $reporte['config'][$param['uuid_objeto']][$param['obj_propiedad']] = $param['value'];
            }

            //Obtener los objetos del header relacionados al id del reporte
            $strSqlObjetos = "
                SELECT 
                    *
                FROM 
                    designer_rel_reporte_objeto_propiedades 
                WHERE 
                    id_reporte = '$id_reporte'
                AND 
                    seccion ='header'";
            $_header = query2array($strSqlObjetos);
            $reporte['_header_'] = array();
            $total_header = sizeof($_header);
            for($x = 0; $x < $total_header; $x++){
                $header = $_header[$x];
                $reporte['_header_'][$header['uuid_objeto']][$header['obj_propiedad']] = $header['value'];
            }

            $reporte['seccion'] = array();
            if( isset($reporte['config']['param']['group_count']) ){
                //Obtener las propiedades de cada grupo de HEADER
                $strSqlObjetos = "
                    SELECT 
                    *
                    FROM 
                    designer_rel_reporte_objeto_propiedades 
                    WHERE 
                    id_reporte = '$id_reporte'
                    AND 
                    (seccion LIKE 'header-group-%' OR seccion LIKE 'footer-group-%' )";

                $groups = query2array($strSqlObjetos);
                $reporte['_groups_params_'] = array();
                $total_groups = sizeof($groups);
                for($x = 0; $x < $total_groups; $x++){
                    $group = $groups[$x];
                    $reporte['_groups_params_'][$group['seccion']]['seccion'] = $group['seccion'];
                    $reporte['_groups_params_'][$group['seccion']][$group['obj_propiedad']] = $group['value'];
                    $reporte['seccion'][$group['seccion']] =  $reporte['_groups_params_'][$group['seccion']];
/*
                    $reporte['_groups_params_'][$group['uuid_objeto']]['seccion'] = $group['seccion'];
                    $reporte['_groups_params_'][$group['uuid_objeto']][$group['obj_propiedad']] = $group['value'];
                    $reporte['seccion'][$group['seccion']] =  $reporte['_groups_params_'][$group['uuid_objeto']];
 */
                    $reporte[$group['seccion']] = array();
                }

                //Obtener los objetos las agrupaciones de HEADER
                $strSqlObjetos = "
                    SELECT 
                    *
                    FROM 
                    designer_rel_reporte_objeto_propiedades 
                    WHERE 
                    id_reporte = '$id_reporte'
                    AND 
                    (seccion LIKE '_header-group-%' OR seccion LIKE '_footer-group-%')";
                $groups = query2array($strSqlObjetos);
                $reporte['_groups_'] = array();
                $total_groups = sizeof($groups);
                for($x = 0; $x < $total_groups; $x++){
                    $group = $groups[$x];
                    $reporte['opts'][$group['uuid_objeto']] = array($group);
                    $reporte['_groups_'][$group['uuid_objeto']][$group['obj_propiedad']] = $group['value'];
                }
/*
                //Obtener las propiedades de cada grupo de FOOTER
                $strSqlObjetos = "
                    SELECT 
                    *
                    FROM 
                    designer_rel_reporte_objeto_propiedades 
                    WHERE 
                    id_reporte = '$id_reporte'
                    AND 
                    seccion LIKE 'footer-group-%'";
                $groups = query2array($strSqlObjetos);
                //$reporte['_groups_params_'] = array();
                $total_groups = sizeof($groups);
                for($x = 0; $x < $total_groups; $x++){
                    $group = $groups[$x];
                    $reporte['_groups_params_'][$group['uuid_objeto']]['seccion'] = $group['seccion'];
                    $reporte['_groups_params_'][$group['uuid_objeto']][$group['obj_propiedad']] = $group['value'];
                    $reporte['seccion'][$group['seccion']] =  $reporte['_groups_params_'][$group['uuid_objeto']];
                    $reporte[$group['seccion']] = array();
                }

                //Obtener los objetos las agrupaciones de FOOTER
                $strSqlObjetos = "
                    SELECT 
                    *
                    FROM 
                    designer_rel_reporte_objeto_propiedades 
                    WHERE 
                    id_reporte = '$id_reporte'
                    AND 
                    seccion LIKE '_footer-group-%'";
                $groups = query2array($strSqlObjetos);
                //$reporte['_groups_'] = array();
                $total_groups = sizeof($groups);
                for($x = 0; $x < $total_groups; $x++){
                    $group = $groups[$x];
                    $reporte['opts'][$group['uuid_objeto']] = array($group);
                    $reporte['_groups_'][$group['uuid_objeto']][$group['obj_propiedad']] = $group['value'];
                }
*/

                foreach($reporte['_groups_'] as $obj){
                    if( $obj['type'] != 'page'){
                        $reporte[str_replace('_', '', $obj['seccion'])][] = $obj['name'];
                        $reporte[$obj['name']] = $obj;
                    }
                }

            }else{
                $reporte['config']['param']['group_count'] = 0;
            }

            //Obtener los objetos del content relacionados al id del reporte
            $strSqlObjetos = "
                SELECT 
                    *
                FROM 
                    designer_rel_reporte_objeto_propiedades 
                WHERE 
                    id_reporte = '$id_reporte'
                AND 
                    seccion ='content'";
            $_content = query2array($strSqlObjetos);
            $reporte['_content_'] = array();
            $total_content = sizeof($_content);
            for($x = 0; $x < $total_content; $x++){
                $content = $_content[$x];
                $reporte['_content_'][$content['uuid_objeto']][$content['obj_propiedad']] = $content['value'];
            }

            $reporte['content'] = array();
            foreach($reporte['_content_'] as $obj){
                if( $obj['type'] != 'page'){
                    $reporte['content'][] = $obj['name'];
                    $reporte[$obj['name']] = $obj;
                }
            }

            //Obtener los objetos del footer relacionados al id del reporte
            $strSqlObjetos = "
                SELECT 
                    *
                FROM 
                    designer_rel_reporte_objeto_propiedades 
                WHERE 
                    id_reporte = '$id_reporte'
                AND 
                    seccion ='footer'";
            $_footer = query2array($strSqlObjetos);
            $reporte['_footer_'] = array();
            $total_footer = sizeof($_footer);
            for($x = 0; $x < $total_footer; $x++){
                $footer = $_footer[$x];
                $reporte['_footer_'][$footer['uuid_objeto']][$footer['obj_propiedad']] = $footer['value'];
            }

            return $reporte;
            
        }

        function remove($id_reporte = 0){
            //Borrar el reporte actual
            $strSqlClear = "DELETE FROM designer_tbl_reportes WHERE id_reporte = '$id_reporte'";
            query($strSqlClear);

            //Borrar las consultas relacionadas al id del reporte
            $strSqlClear = "DELETE FROM designer_rel_reporte_sql WHERE id_reporte = '$id_reporte'";
            query($strSqlClear);

            //Borrar los objetos relacionados al id del reporte
            $strSqlClear = "DELETE FROM designer_rel_reporte_objeto_propiedades WHERE id_reporte = '$id_reporte'";
            query($strSqlClear);

        }

        function Header(){
            if(isset($this->rpt['header'])){
                $this->_RenderHeader($this->rpt['header']);
            }
        }

        function Footer(){
            //Position at 1.5 cm from bottom
            //$this->SetY(-15);
            $this->SetY(isset_or($this->rpt['document']['footer_height'])*-1);
            //Arial italic 8
            //$this->SetFont('Arial','I',8);
            //Text color in gray
            //$this->SetTextColor(128);
            //Page number
            //$this->Cell(0,10,'Page '.$this->PageNo().' '.$this->GetY(),0,0,'C');
            if(isset($this->rpt['footer'])){
                $this->_RenderFooter($this->rpt['footer'],$this->GetY());
            }
        }

        function Render($rpt){
            @ob_end_clean();           
            $this->rpt=$rpt;
            $document=array();
            $document['orientation']='P';
            $document['unit']='mm';
            $document['format']='A4';
            $document['left_margin']='0';
            $document['top_margin']='0';
            $document['filename']='doc.pdf';

            if(isset($this->rpt['document'])){
                foreach($this->rpt['document'] as $property=>$value){
                    if(isset($document[$property])){
                        $document[$property]=$value;
                    }
                }
            }
            $this->document = $document;

            $this->FPDF($document['orientation'],$document['unit'],$document['format']);
            $this->setMargins($document['left_margin'],$document['top_margin']);
            $this->PDF_Code128();
            $this->AliasNbPages();
            $this->SetTitle(" ");
            $this->SetAutoPageBreak(false);
            $this->SetAuthor('http://www.deliriumlabs.net');

            if(isset($this->rpt['content'])){
                $this->_RenderContent($this->rpt['content']);
            }
            $this->Output($document['filename'], 'I');

        }

        function _RenderContent($content){
            foreach($content as $obj=>$property){
                switch($property['type']){
                case "lbl":
                    $this->lbl($property);
                    break;

                    case "lbl2";
                    $this->lbl2($property);
                    break;

                case "image":
                    if(isset($property['h'])){
                        $this->Image($property['url'],$property['x'],$property['y'],$property['w'],$property['h']);
                    }else{
                        $this->Image($property['url'],$property['x'],$property['y'],$property['w']);
                    }
                    break;

                case "page":
                    $this->AddPage();
                    break;

                case "barcode":
                    $this->SetDrawColor(0);
                    $this->SetFillColor(0);
                    $this->SetTextColor(0);
                    if( isset($property['width']) ){
                        $property['w'] = $property['width'];
                        $property['h'] = $property['height'];
                    }
                    $this->Code128($property['x'],$property['y'],  $property['code'], $property['w'] ,$property['h']);
                    break;

                case "ln":
                    if(isset($property['height'])){
                        $this->ln($property['height']);
                    }else{
                        $this->ln();
                    }
                    break;
                }
            }
        }

        function _RenderHeader($header){
            foreach($header as $obj=>$property){
                switch($property['type']){
                case "lbl":
                    $this->lbl($property);
                    break;

                    case "lbl2";
                    $this->lbl2($property);
                    break;

                case "image":
                    if(isset($property['h'])){
                        $this->Image($property['url'],$property['x'],$property['y'],$property['w'],$property['h']);
                    }else{
                        $this->Image($property['url'],$property['x'],$property['y'],$property['w']);
                    }
                    break;

                case "page":
                    $this->AddPage();
                    break;

                case "barcode":
                    $this->SetDrawColor(0);
                    $this->SetFillColor(0);
                    $this->SetTextColor(0);
                    if( isset($property['width']) ){
                        $property['w'] = $property['width'];
                        $property['h'] = $property['height'];
                    }
                    $this->Code128($property['x'],$property['y'],  $property['code'], $property['w'] ,$property['h']);
                    break;
                case "ln":
                    $this->ln();
                    break;
                }
            }
        }

        function _RenderFooter($footer,$ajuste_y = 0){
            foreach($footer as $obj=>$property){
                switch($property['type']){
                case "lbl":
                    $this->lbl($property,$ajuste_y);
                    break;

                    case "lbl2";
                    $this->lbl2($property,$ajuste_y);
                    break;

                case "image":
                    if(isset($property['h'])){
                        $this->Image($property['url'],$property['x'],$property['y'],$property['w'],$property['h']);
                    }else{
                        $this->Image($property['url'],$property['x'],$property['y'],$property['w']);
                    }
                    break;

                case "page":
                    $this->AddPage();
                    break;
                case "barcode":
                    $this->SetDrawColor(0);
                    $this->SetFillColor(0);
                    $this->SetTextColor(0);
                    if( isset($property['width']) ){
                        $property['w'] = $property['width'];
                        $property['h'] = $property['height'];
                    }
                    $this->Code128($property['x'],$property['y'],  $property['code'], $property['w'] ,$property['h']);
                    break;
                case "ln":
                    $this->ln();
                    break;
                }
            }
        }

        function lbl($_lbl){
            $lbl['width']="";
            $lbl['height']="";
            $lbl['border']="0";
            $lbl['align']="C";
            $lbl['fill']="1";
            $lbl['txt']="";

            $lbl['font-family']='Arial';
            $lbl['font-weight']='B';
            $lbl['font-size']=15;
            $lbl['font-color-R']=0;
            $lbl['font-color-G']=0;
            $lbl['font-color-B']=0;
            $lbl['font-bgcolor-R']=255;
            $lbl['font-bgcolor-G']=255;
            $lbl['font-bgcolor-B']=255;

            $lbl['border-color-R']=0;
            $lbl['border-color-G']=0;
            $lbl['border-color-B']=0;
            $lbl['border-size']=0;

            $lbl['x']=0;
            $lbl['y']=0;

            foreach($_lbl as $property=>$value){
                if(isset($lbl[$property])){
                    $lbl[$property]=$value;
                }
            }

            $this->SetFont($lbl['font-family'],$lbl['font-weight'],$lbl['font-size']);
            if($lbl['width'] ==""){
                $lbl['width']=$this->GetStringWidth($lbl['txt'])+6;
            }

            //Colors of frame, background and text
            $this->SetDrawColor($lbl['border-color-R'],$lbl['border-color-G'],$lbl['border-color-B']);
            $this->SetFillColor($lbl['font-bgcolor-R'],$lbl['font-bgcolor-G'],$lbl['font-bgcolor-B']);
            $this->SetTextColor($lbl['font-color-R'],$lbl['font-color-G'],$lbl['font-color-B']);
            //Thickness of frame (1 mm)
            $this->SetLineWidth($lbl['border-size']);
            $this->SetXY($lbl['x'],$lbl['y']);

            $this->MultiCell($lbl['width'],$lbl['height'],$lbl['txt'],$lbl['border'],$lbl['align'],$lbl['fill']);

        }

        function lbl2($_lbl,$ajuste_y=0){
            $lbl['width']="";
            $lbl['height']="";
            $lbl['border']="0";
            $lbl['align']="";
            $lbl['fill']="0";
            $lbl['txt']="";

            $lbl['font-family']='Arial';
            $lbl['font-bold']='';
            $lbl['font-italic']='';
            $lbl['font-underline']='';
            $lbl['font-size']=15;
            $lbl['font-color-R']=0;
            $lbl['font-color-G']=0;
            $lbl['font-color-B']=0;
            $lbl['font-bgcolor-R']=255;
            $lbl['font-bgcolor-G']=255;
            $lbl['font-bgcolor-B']=255;

            $lbl['border-color-R']=0;
            $lbl['border-color-G']=0;
            $lbl['border-color-B']=0;

            $lbl['border-color-R']=0;
            $lbl['border-color-G']=0;
            $lbl['border-color-B']=0;
            $lbl['border-size']=0;

            $lbl['x']=0;
            $lbl['y']=0;

            foreach($_lbl as $property=>$value){
                if(isset($lbl[$property])){
                    $lbl[$property]=$value;
                }
            }

            $lbl['font-size'] = str_replace('pt', '', $lbl['font-size']);

            $this->SetFont($lbl['font-family'], $lbl['font-bold'].$lbl['font-italic'].$lbl['font-underline'],$lbl['font-size']);
            if($lbl['width'] ==""){
                $lbl['width']=$this->GetStringWidth($lbl['txt'])+6;
            }

            //Colors of frame, background and text
            $this->SetDrawColor($lbl['border-color-R'],$lbl['border-color-G'],$lbl['border-color-B']);
            $this->SetFillColor($lbl['font-bgcolor-R'],$lbl['font-bgcolor-G'],$lbl['font-bgcolor-B']);
            $this->SetTextColor($lbl['font-color-R'],$lbl['font-color-G'],$lbl['font-color-B']);
            //Thickness of frame (1 mm)
            $this->SetLineWidth($lbl['border-size']);

            if($lbl['x'] != "" &&$lbl['y'] != "" ){
                $this->SetXY($lbl['x'], ($lbl['y']+ $ajuste_y));
            }else{
                if($lbl['x'] != ""){
                    $this->SetX($lbl['x']);
                }

                if($lbl['y'] != ""){
                    $this->SetY($lbl['y'] + $ajuste_y);
                }
            
            }

            $this->MultiCell($lbl['width'],$lbl['height'],$lbl['txt'], $lbl['border'], $lbl['align'], $lbl['fill']);

            //$this->Cell($lbl['width'],$lbl['height'],$lbl['txt'], $lbl['border'], 0, $lbl['align'], $lbl['fill']);

        }

        function PDF_Code128() {



            $this->T128[] = array(2, 1, 2, 2, 2, 2);           //0 : [ ]               // composition des caractères
            $this->T128[] = array(2, 2, 2, 1, 2, 2);           //1 : [!]
            $this->T128[] = array(2, 2, 2, 2, 2, 1);           //2 : ["]
            $this->T128[] = array(1, 2, 1, 2, 2, 3);           //3 : [#]
            $this->T128[] = array(1, 2, 1, 3, 2, 2);           //4 : [$]
            $this->T128[] = array(1, 3, 1, 2, 2, 2);           //5 : [%]
            $this->T128[] = array(1, 2, 2, 2, 1, 3);           //6 : [&]
            $this->T128[] = array(1, 2, 2, 3, 1, 2);           //7 : [']
            $this->T128[] = array(1, 3, 2, 2, 1, 2);           //8 : [(]
            $this->T128[] = array(2, 2, 1, 2, 1, 3);           //9 : [)]
            $this->T128[] = array(2, 2, 1, 3, 1, 2);           //10 : [*]
            $this->T128[] = array(2, 3, 1, 2, 1, 2);           //11 : [+]
            $this->T128[] = array(1, 1, 2, 2, 3, 2);           //12 : [,]
            $this->T128[] = array(1, 2, 2, 1, 3, 2);           //13 : [-]
            $this->T128[] = array(1, 2, 2, 2, 3, 1);           //14 : [.]
            $this->T128[] = array(1, 1, 3, 2, 2, 2);           //15 : [/]
            $this->T128[] = array(1, 2, 3, 1, 2, 2);           //16 : [0]
            $this->T128[] = array(1, 2, 3, 2, 2, 1);           //17 : [1]
            $this->T128[] = array(2, 2, 3, 2, 1, 1);           //18 : [2]
            $this->T128[] = array(2, 2, 1, 1, 3, 2);           //19 : [3]
            $this->T128[] = array(2, 2, 1, 2, 3, 1);           //20 : [4]
            $this->T128[] = array(2, 1, 3, 2, 1, 2);           //21 : [5]
            $this->T128[] = array(2, 2, 3, 1, 1, 2);           //22 : [6]
            $this->T128[] = array(3, 1, 2, 1, 3, 1);           //23 : [7]
            $this->T128[] = array(3, 1, 1, 2, 2, 2);           //24 : [8]
            $this->T128[] = array(3, 2, 1, 1, 2, 2);           //25 : [9]
            $this->T128[] = array(3, 2, 1, 2, 2, 1);           //26 : [:]
            $this->T128[] = array(3, 1, 2, 2, 1, 2);           //27 : [;]
            $this->T128[] = array(3, 2, 2, 1, 1, 2);           //28 : [<]
            $this->T128[] = array(3, 2, 2, 2, 1, 1);           //29 : [=]
            $this->T128[] = array(2, 1, 2, 1, 2, 3);           //30 : [>]
            $this->T128[] = array(2, 1, 2, 3, 2, 1);           //31 : [?]
            $this->T128[] = array(2, 3, 2, 1, 2, 1);           //32 : [@]
            $this->T128[] = array(1, 1, 1, 3, 2, 3);           //33 : [A]
            $this->T128[] = array(1, 3, 1, 1, 2, 3);           //34 : [B]
            $this->T128[] = array(1, 3, 1, 3, 2, 1);           //35 : [C]
            $this->T128[] = array(1, 1, 2, 3, 1, 3);           //36 : [D]
            $this->T128[] = array(1, 3, 2, 1, 1, 3);           //37 : [E]
            $this->T128[] = array(1, 3, 2, 3, 1, 1);           //38 : [F]
            $this->T128[] = array(2, 1, 1, 3, 1, 3);           //39 : [G]
            $this->T128[] = array(2, 3, 1, 1, 1, 3);           //40 : [H]
            $this->T128[] = array(2, 3, 1, 3, 1, 1);           //41 : [I]
            $this->T128[] = array(1, 1, 2, 1, 3, 3);           //42 : [J]
            $this->T128[] = array(1, 1, 2, 3, 3, 1);           //43 : [K]
            $this->T128[] = array(1, 3, 2, 1, 3, 1);           //44 : [L]
            $this->T128[] = array(1, 1, 3, 1, 2, 3);           //45 : [M]
            $this->T128[] = array(1, 1, 3, 3, 2, 1);           //46 : [N]
            $this->T128[] = array(1, 3, 3, 1, 2, 1);           //47 : [O]
            $this->T128[] = array(3, 1, 3, 1, 2, 1);           //48 : [P]
            $this->T128[] = array(2, 1, 1, 3, 3, 1);           //49 : [Q]
            $this->T128[] = array(2, 3, 1, 1, 3, 1);           //50 : [R]
            $this->T128[] = array(2, 1, 3, 1, 1, 3);           //51 : [S]
            $this->T128[] = array(2, 1, 3, 3, 1, 1);           //52 : [T]
            $this->T128[] = array(2, 1, 3, 1, 3, 1);           //53 : [U]
            $this->T128[] = array(3, 1, 1, 1, 2, 3);           //54 : [V]
            $this->T128[] = array(3, 1, 1, 3, 2, 1);           //55 : [W]
            $this->T128[] = array(3, 3, 1, 1, 2, 1);           //56 : [X]
            $this->T128[] = array(3, 1, 2, 1, 1, 3);           //57 : [Y]
            $this->T128[] = array(3, 1, 2, 3, 1, 1);           //58 : [Z]
            $this->T128[] = array(3, 3, 2, 1, 1, 1);           //59 : [[]
            $this->T128[] = array(3, 1, 4, 1, 1, 1);           //60 : [\]
            $this->T128[] = array(2, 2, 1, 4, 1, 1);           //61 : []]
            $this->T128[] = array(4, 3, 1, 1, 1, 1);           //62 : [^]
            $this->T128[] = array(1, 1, 1, 2, 2, 4);           //63 : [_]
            $this->T128[] = array(1, 1, 1, 4, 2, 2);           //64 : [`]
            $this->T128[] = array(1, 2, 1, 1, 2, 4);           //65 : [a]
            $this->T128[] = array(1, 2, 1, 4, 2, 1);           //66 : [b]
            $this->T128[] = array(1, 4, 1, 1, 2, 2);           //67 : [c]
            $this->T128[] = array(1, 4, 1, 2, 2, 1);           //68 : [d]
            $this->T128[] = array(1, 1, 2, 2, 1, 4);           //69 : [e]
            $this->T128[] = array(1, 1, 2, 4, 1, 2);           //70 : [f]
            $this->T128[] = array(1, 2, 2, 1, 1, 4);           //71 : [g]
            $this->T128[] = array(1, 2, 2, 4, 1, 1);           //72 : [h]
            $this->T128[] = array(1, 4, 2, 1, 1, 2);           //73 : [i]
            $this->T128[] = array(1, 4, 2, 2, 1, 1);           //74 : [j]
            $this->T128[] = array(2, 4, 1, 2, 1, 1);           //75 : [k]
            $this->T128[] = array(2, 2, 1, 1, 1, 4);           //76 : [l]
            $this->T128[] = array(4, 1, 3, 1, 1, 1);           //77 : [m]
            $this->T128[] = array(2, 4, 1, 1, 1, 2);           //78 : [n]
            $this->T128[] = array(1, 3, 4, 1, 1, 1);           //79 : [o]
            $this->T128[] = array(1, 1, 1, 2, 4, 2);           //80 : [p]
            $this->T128[] = array(1, 2, 1, 1, 4, 2);           //81 : [q]
            $this->T128[] = array(1, 2, 1, 2, 4, 1);           //82 : [r]
            $this->T128[] = array(1, 1, 4, 2, 1, 2);           //83 : [s]
            $this->T128[] = array(1, 2, 4, 1, 1, 2);           //84 : [t]
            $this->T128[] = array(1, 2, 4, 2, 1, 1);           //85 : [u]
            $this->T128[] = array(4, 1, 1, 2, 1, 2);           //86 : [v]
            $this->T128[] = array(4, 2, 1, 1, 1, 2);           //87 : [w]
            $this->T128[] = array(4, 2, 1, 2, 1, 1);           //88 : [x]
            $this->T128[] = array(2, 1, 2, 1, 4, 1);           //89 : [y]
            $this->T128[] = array(2, 1, 4, 1, 2, 1);           //90 : [z]
            $this->T128[] = array(4, 1, 2, 1, 2, 1);           //91 : [{]
            $this->T128[] = array(1, 1, 1, 1, 4, 3);           //92 : [|]
            $this->T128[] = array(1, 1, 1, 3, 4, 1);           //93 : [}]
            $this->T128[] = array(1, 3, 1, 1, 4, 1);           //94 : [~]
            $this->T128[] = array(1, 1, 4, 1, 1, 3);           //95 : [DEL]
            $this->T128[] = array(1, 1, 4, 3, 1, 1);           //96 : [FNC3]
            $this->T128[] = array(4, 1, 1, 1, 1, 3);           //97 : [FNC2]
            $this->T128[] = array(4, 1, 1, 3, 1, 1);           //98 : [SHIFT]
            $this->T128[] = array(1, 1, 3, 1, 4, 1);           //99 : [Cswap]
            $this->T128[] = array(1, 1, 4, 1, 3, 1);           //100 : [Bswap]                
            $this->T128[] = array(3, 1, 1, 1, 4, 1);           //101 : [Aswap]
            $this->T128[] = array(4, 1, 1, 1, 3, 1);           //102 : [FNC1]
            $this->T128[] = array(2, 1, 1, 4, 1, 2);           //103 : [Astart]
            $this->T128[] = array(2, 1, 1, 2, 1, 4);           //104 : [Bstart]
            $this->T128[] = array(2, 1, 1, 2, 3, 2);           //105 : [Cstart]
            $this->T128[] = array(2, 3, 3, 1, 1, 1);           //106 : [STOP]
            $this->T128[] = array(2, 1);                       //107 : [END BAR]

            for ($i = 32; $i <= 95; $i++) {                                            // jeux de caractères
                $this->ABCset .= chr($i);
            }
            $this->Aset = $this->ABCset;
            $this->Bset = $this->ABCset;
            for ($i = 0; $i <= 31; $i++) {
                $this->ABCset .= chr($i);
                $this->Aset .= chr($i);
            }
            for ($i = 96; $i <= 126; $i++) {
                $this->ABCset .= chr($i);
                $this->Bset .= chr($i);
            }
            $this->Cset="0123456789";

            for ($i=0; $i<96; $i++) {                                                  // convertisseurs des jeux A & B  
                @$this->SetFrom["A"] .= chr($i);
                @$this->SetFrom["B"] .= chr($i + 32);
                @$this->SetTo["A"] .= chr(($i < 32) ? $i+64 : $i-32);
                @$this->SetTo["B"] .= chr($i);
            }
        }

        //________________ Fonction encodage et dessin du code 128 _____________________
        function Code128($x,$y,$code,$w,$h) {
            $Aguid="";                                                                      // Création des guides de choix ABC
            $Bguid="";
            $Cguid="";
            for ($i=0; $i < strlen($code); $i++) {
                $needle=substr($code,$i,1);
                $Aguid .= ((strpos($this->Aset,$needle)===FALSE) ? "N" : "O"); 
                $Bguid .= ((strpos($this->Bset,$needle)===FALSE) ? "N" : "O"); 
                $Cguid .= ((strpos($this->Cset,$needle)===FALSE) ? "N" : "O");
            }

            $SminiC = "OOOO";
            $IminiC = 4;

            $crypt = "";
            while ($code > "") {
                // BOUCLE PRINCIPALE DE CODAGE
                $i = strpos($Cguid,$SminiC);                                                // forçage du jeu C, si possible
                if ($i!==FALSE) {
                    $Aguid [$i] = "N";
                    $Bguid [$i] = "N";
                }

                if (substr($Cguid,0,$IminiC) == $SminiC) {                                  // jeu C
                    $crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);  // début Cstart, sinon Cswap
                    $made = strpos($Cguid,"N");                                             // étendu du set C
                    if ($made === FALSE) $made = strlen($Cguid);
                    if (fmod($made,2)==1) $made--;                                          // seulement un nombre pair
                    for ($i=0; $i < $made; $i += 2) $crypt .= chr(strval(substr($code,$i,2))); // conversion 2 par 2
                    $jeu = "C";
                } else {
                    $madeA = strpos($Aguid,"N");                                            // étendu du set A
                    if ($madeA === FALSE) $madeA = strlen($Aguid);
                    $madeB = strpos($Bguid,"N");                                            // étendu du set B
                    if ($madeB === FALSE) $madeB = strlen($Bguid);
                    $made = (($madeA < $madeB) ? $madeB : $madeA );                         // étendu traitée
                    $jeu = (($madeA < $madeB) ? "B" : "A" );                                // Jeu en cours
                    $jeuguid = $jeu . "guid";

                    $crypt .= chr(($crypt > "") ? $this->JSwap["$jeu"] : $this->JStart["$jeu"]); // début start, sinon swap

                    $crypt .= strtr(substr($code, 0,$made), $this->SetFrom[$jeu], $this->SetTo[$jeu]); // conversion selon jeu

                }
                $code = substr($code,$made);                                           // raccourcir légende et guides de la zone traitée
                $Aguid = substr($Aguid,$made);
                $Bguid = substr($Bguid,$made);
                $Cguid = substr($Cguid,$made);
            }                                                                          // FIN BOUCLE PRINCIPALE

            $check=ord($crypt[0]);                                                     // calcul de la somme de contrôle
            for ($i=0; $i<strlen($crypt); $i++) {
                $check += (ord($crypt[$i]) * $i);
            }
            $check %= 103;

            $crypt .= chr($check) . chr(106) . chr(107);                               // Chaine Cryptée complète

            $i = (strlen($crypt) * 11) - 8;                                            // calcul de la largeur du module
            $modul = $w/$i;

            for ($i=0; $i<strlen($crypt); $i++) {                                      // BOUCLE D'IMPRESSION
                $c = $this->T128[ord($crypt[$i])];
                for ($j=0; $j<count($c); $j++) {
                    $this->Rect($x,$y,$c[$j]*$modul,$h,"F");
                    $x += ($c[$j++]+$c[$j])*$modul;
                }
            }
        }

    }
}
?>
