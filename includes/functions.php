<?php
function main($contenido,$head=array(),$css=""){
    $ctrl = new Controller_Base();
    $ctrl->loadController('index'); 
    $index= new Controller_Index("","index");

    $index->index($contenido,$head,$css);
}

function suggest($results){
    if (sizeof($results)==0) {
        $results[0]=array('id'=>'','val'=>'','info'=>'No se encontraron resultados');
    }
    return array2json($results);
}

function date2mysql($fecha){
    $fecha_return='0000-00-00';
    $fecha=str_replace('/','-',$fecha);
    $fecha=explode('-',$fecha);
    if(sizeof($fecha)==3){
        list($dia,$mes,$anio)=$fecha;
        $fecha_return="$anio-$mes-$dia";
    }

    return $fecha_return;
}

function date_from_mysql($fecha){
    $fecha_return='00-00-0000';
    $fecha=str_replace('/','-',$fecha);
    $fecha=explode('-',$fecha);
    if(sizeof($fecha)==3){
        list($anio,$mes,$dia)=$fecha;
        $fecha_return="$dia-$mes-$anio";
    }

    return $fecha_return;
}

function search($data){
    $index= new Controller_DeliriumSearch('','deliriumsearch');
    $arr['search']=$index->getsearch($data);
    return $arr;
}

function thumbnail_db($im,$max=500,$crop=false){
    //ini_set("memory_limit","12M");
    $source_x  = imagesx($im);
    $source_y = imagesy($im);

    $radioW=$max/$source_x;
    $radioH=$max/$source_y;

    if ( ($source_x <= $max) && ($source_y <= $max) ){
        // LA IMAGEN ES MAS PEQUEï¿½A QUE EL TAMAÃ‘O DESEADO
        //print $im;
        $out = ImagejpeG($im);
        print $out;
        return false;
    }elseif ( ($radioW * $source_y) < $max ){
        // ES UNA IMAGEN HORIZONTAL
        $dest_y = ceil($radioW * $source_y);
        $dest_x = $max;
    }else{
        // ES UNA IMAGEN VERTICAL
        $dest_x = ceil($radioH * $source_x);
        $dest_y = $max;
    }

    // CREAMOS UNA IMAGEN NUEVA CON LAS DIMENSIONES ESPECIFICADAS
    $thumb=imagecreatetruecolor($dest_x,$dest_y);

    $back = imagecolorallocate($thumb, 255, 255, 255);
    imagefill ( $thumb, 0, 0, $back );
    if($crop){
        $newwidth = $source_x / 2;
        $newheight = $source_y / 2;
        $cropLeft = ($newwidth/2) - ($max/2);
        $cropHeight = ($newheight/2) - ($max/2);
        ImageCopyResampled($thumb,$im,0,0,$cropLeft,$cropHeight,$dest_x,$dest_y,$newwidth,$newheight);
    }else{
        // copy original image to thumbnail
        ImageCopyResampled($thumb,$im,0,0,0,0,$dest_x,$dest_y,$source_x,$source_y);
    }

    // show thumbnail on screen
    $out = ImagejpeG($thumb,'',100);
    //print($out);

    // clean memory
    imagedestroy ($im);
    imagedestroy ($thumb);
    //Y POR ULTIMO SIMPLEMENTE IMPRIMIMOS EL CONTENIDO DEL ARCHIVO
    print $out;
}

/* resizeToFile resizes a picture and writes it to the harddisk
 *
 * $sourcefile = the filename of the picture that is going to be resized
 * $dest_x       = X-Size of the target picture in pixels
 * $dest_y       = Y-Size of the target picture in pixels
 * $targetfile = The name under which the resized picture will be stored
 * $jpegqual   = The Compression-Rate that is to be used
 */

function resizeToFile ($sourcefile, $max, $targetfile, $jpegqual=80)
{
    /* OBTENER LAS DIMENSIONES ACTUALES */
    $picsize=@getimagesize("$sourcefile");

    $source_x  = $picsize[0];
    $source_y  = $picsize[1];
    $source_id = @openImage("$sourcefile");

    $radioW=$max/$source_x;
    $radioH=$max/$source_y;


    // CALCULAR LA ALTURA Y ANCHO PROPORCIONALES

    if ( ($source_x <= $max) && ($source_y <= $max) ){
        // LA IMAGEN ES MAS PEQUEï¿½A QUE EL TAMAï¿½O DESEADO
        return $sourcefile;
    }elseif ( ($radioW * $source_y) < $max ){
        // ES UNA IMAGEN HORIZONTAL
        $dest_y = ceil($radioW * $source_y);
        $dest_x = $max;
    }else{
        // ES UNA IMAGEN VERTICAL
        $dest_x = ceil($radioH * $source_x);
        $dest_y = $max;
    }

    // CREAMOS UNA IMAGEN NUEVA CON LAS DIMENSIONES ESPECIFICADAS
    $target_id=imagecreatetruecolor($dest_x, $dest_y);


    /* Resize the original picture and copy it into the just created image
    object. Because of the lack of space I had to wrap the parameters to
    several lines. I recommend putting them in one line in order keep your
    code clean and readable */
    $back = imagecolorallocate($target_id, 255, 255, 255);
    imagefill ( $target_id, 0, 0, $back );

    $target_pic=imagecopyresampled($target_id,$source_id,0,0,0,0,$dest_x,$dest_y,$source_x,$source_y);
/* Create a jpeg with the quality of "$jpegqual" out of the
   image object "$target_pic".
This will be saved as $targetfile */


    @imagejpeg ($target_id,"$targetfile",$jpegqual);

    return true;

}

function openImage($file){

    # GIF:
    $im = @imagecreatefromgif($file);
    if ($im !== false) { return $im; }

    # PNG:
    $im = @imagecreatefrompng($file);
    if ($im !== false) { return $im; }

    # JPEG:
    $im = imagecreatefromjpeg($file);
    if ($im !== false) { return $im; }

    # GD File:
    $im = @imagecreatefromgd($file);
    if ($im !== false) { return $im; }

    # GD2 File:
    $im = @imagecreatefromgd2($file);
    if ($im !== false) { return $im; }

    # WBMP:
    $im = @imagecreatefromwbmp($file);
    if ($im !== false) { return $im; }

    # XBM:
    $im = @imagecreatefromxbm($file);
    if ($im !== false) { return $im; }

    # XPM:
    $im = @imagecreatefromxpm($file);
    if ($im !== false) { return $im; }

    # Try and load from string:
    $im = @imagecreatefromstring(file_get_contents($file));
    if ($im !== false) { return $im; }

    return false;
}


//ESTA FUNCION LA USAREMOS PARA OBTENER EL TAMAï¿½O DE NUESTRO ARCHIVO
function filesize_format($bytes, $format = '', $force = ''){
    $bytes=(float)$bytes;
    if ($bytes <1024){
        $numero=number_format($bytes, 0, '.', ',');
        return array($numero,"B");
    }
    if ($bytes <1048576){
        $numero=number_format($bytes/1024, 2, '.', ',');
        return array($numero,"KBs");
    }
    if ($bytes>= 1048576){
        $numero=number_format($bytes/1048576, 2, '.', ',');
        return array($numero,"MB");
    }
}

function editor($id,$default='',$toolbar='Default',$width=0){
    $oFCKeditor = new FCKeditor($id) ;
    $basePath="extra/fckeditor/";
    $oFCKeditor->BasePath=$basePath;
    $oFCKeditor->Value= $default;
    $oFCKeditor->ToolbarSet=$toolbar;
    if ($width>0) {
        $oFCKeditor->Width=$width;
    }
    $oFCKeditor->Height=600;

    $output = $oFCKeditor->CreateHtml() ; echo $output;
}

function parse_email ($email) {
    // Split header and message
    $header = array();
    $message = array();

    $is_header = true;
    foreach ($email as $line) {
        if ($line == '<HEADER> ' . "\r\n") continue;
        if ($line == '<MESSAGE> ' . "\r\n") continue;
        if ($line == '</MESSAGE> ' . "\r\n") continue;
        if ($line == '</HEADER> ' . "\r\n") { $is_header = false; continue; }

        if ($is_header == true) {
            $header[] = $line;
        } else {
            $message[] = $line;
        }
    }

    // Parse headers
    $headers = array();
    foreach ($header as $line) {
        $colon_pos = strpos($line, ':');
        $space_pos = strpos($line, ' ');

        if ($colon_pos === false OR $space_pos < $colon_pos) {
            // attach to previous
            $previous .= "\r\n" . $line;
            continue;
        }

        // Get key
        $key = substr($line, 0, $colon_pos);

        // Get value
        $value = substr($line, $colon_pos+2);
        $headers[$key] = $value;

        $previous =& $headers[$key];
    }

    // Parse message
    $message = implode('', $message);

    // Return array
    $email = array();
    $email['message'] = $message;
    $email['headers'] = $headers;

    return $email;
}

function sql_str_safe($data,$native=0){

    if(is_array($data)){
        foreach($data as $key=>$value){
            if(is_string($data[$key])&&$key!='base'){
                $data[$key]=sql_str_safe($data[$key]);
            }
        }
    }else{
        if ( function_exists( 'mysql_real_escape_string' ) && $native==1){
            $data = mysql_real_escape_string($data);
        } else {
            $data = addslashes($data);
        }

    }
    return $data;

}

if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);

        return $value;
    }

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

function money2number($money){
    return str_replace(array('$',','),'',$money);
}

function recursive_print ($varname, $varval,$br='') {
    if (! is_array($varval)):
        print $varname . ' = ' . var_export($varval, true) . ";$br\n";
    else:
        print $varname . " = array();$br\n";
    foreach ($varval as $key => $val):
        recursive_print ($varname . "[" . var_export($key, true) . "]", $val);
endforeach;
endif;
}

function debug_array($array){
    echo '<pre>';
    var_dump($array);
    echo '</pre>';
}

function array_group($o_arr, $key = ''){
    $r_arr = array();
    $total_items = sizeof($o_arr);
    for ( $x=0; $x < $total_items; $x++ ){ 
        $item = $o_arr[$x]; 
        if( !isset($r_arr[$item[$key]]) ){
            $r_arr[$item[$key]] = array();
            $r_arr[$item[$key]]['items'] = array($item);
            $r_arr[$item[$key]]['total'] = 1;
        }else{
            $r_arr[$item[$key]]['items'][] = $item;
            $r_arr[$item[$key]]['total'] += 1;
        }
    }
    return $r_arr;

}

function array_sum_key( $arr, $index = null ){
    if(!is_array( $arr ) || sizeof( $arr ) < 1){
        return 0;
    }
    $ret = 0;
    foreach( $arr as $id => $data ){
        if( isset( $index )  ){
            $ret += (isset( $data[$index] )) ? money2number($data[$index]) : 0;
        }else{
            $ret += money2number($data);
        }
    }
    return $ret;
}

/**
 * number2text
 *
 * $importe_letra=strtoupper(number2text(number_format($monto, 3, ".", "")));
 * 
 * @param mixed $valor 
 * @access public
 * @return void
 */
function number2text($valor){

    $valor = str_replace(array("$",","),"",$valor);
    $valor = strtoupper(number_format($valor, 2,'.',''));
    global $convertir;

    $longitud = 0;
    $strmi = 0;
    $aux = 0;
    $res = 0;
    $divisor = 0;
    $cents = 0;
    $pesos = 0;
    $num = 0;
    $mil = "mil ";
    $aux = "";
    $dividendo = 0;
    $resultado = 0;
    $unidades = 0;
    $decenas = 0;
    $centenas = 0;
    $miles = 0;
    $demiles = 0;
    $cemiles = 0;
    $millon = 0;
    $demillon = 0;
    $Total = "";
    $conta = 0;
    $strun = "";
    $strde = "";
    $strce = "";
    $strmiles = "";
    $strdemiles = "";
    $strcemiles = "";
    $strmillon = "";
    $strdemillon = "";
    $strcemillon = "";

    $num = $valor;
    $longitud = strlen($num);
    //Le cambiamos a -3,2 para que pudiera regresar bien los centavos antes estaba a -2,2
    $cents = substr($num, $longitud - 2, 2);
    $pesos = " pesos " . $cents . "/100" . " MN";

    $num = substr($num, 0, $longitud - 3);
    $longitud = strlen($num);
    $dividendo = intval($num); 

    While ($conta < $longitud && $longitud < 9){

        $resultado = intval($dividendo / 10);
        $res = $dividendo % 10;

        switch($conta){
            Case 0:
                $unidades = $res;break;
            Case 1:
                $decenas = $res;break;
            Case 2:
                $centenas = $res;break;
            Case 3:
                $miles = $res;break;
            Case 4:
                $demiles = $res;break;
            Case 5:
                $cemiles = $res;break;
            Case 6:
                $millon = $res;break;
            Case 7:
                $demillon = $res;break;
        }
        $dividendo = $resultado;
        $res = 0;
        $conta += 1;
    }

    switch($unidades){
        Case 1:
            $strun = "un ";break;
        Case 2:
            $strun = "dos ";break;
        Case 3:
            $strun = "tres ";break;
        Case 4:
            $strun = "cuatro ";break;
        Case 5:
            $strun = "cinco ";break;
        Case 6:
            $strun = "seis ";break;
        Case 7:
            $strun = "siete ";break;
        Case 8:
            $strun = "ocho ";break;
        Case 9:
            $strun = "nueve ";break;
    }

    $aux = $Total;
    $Total = $strun . $Total;
    switch($decenas){
        Case 1:
            $strde = " dieci";break;
        Case 2:
            $strde = " veinti";break;
        Case 3:
            $strde = " treinta y ";break;
        Case 4:
            $strde = " cuarenta y ";break;
        Case 5:
            $strde = " cincuenta y ";break;
        Case 6:
            $strde = " sesenta y ";break;
        Case 7:
            $strde = " setenta y ";break;
        Case 8:
            $strde = " ochenta y ";break;
        Case 9:
            $strde = " noventa y ";break;
    }
    $aux = $Total;
    $Total = $strde . $Total;

    if($longitud >= 2){
        if($decenas == 1){
            if($unidades == 0){
                $Total = "";
                $Total = $Total . "diez ";
            }
            if($unidades == 1){
                $Total = "";
                $Total = $Total . "once ";
            }
            if($unidades == 2){
                $Total = "";
                $Total = $Total . "doce ";
            }
            if($unidades == 3){
                $Total = "";
                $Total = $Total . "trece ";
            }
            if($unidades == 4){
                $Total = "";
                $Total = $Total . "catorce ";
            }
            if($unidades == 5){
                $Total = "";
                $Total = $Total . "quince ";
            }
        }

        if($decenas == 2){
            if($unidades == 0){
                $Total = "";
                $Total = $Total . "veinte ";
            }
        }
        if($decenas == 3){
            if($unidades == 0){
                $Total = "";
                $Total = $Total . "treinta ";
            }
        }
        if($decenas == 4){
            if($unidades == 0){
                $Total = "";
                $Total = $Total . "cuarenta ";
            }
        }
        if($decenas == 5){
            if($unidades == 0){
                $Total = "";
                $Total = $Total . "cincuenta ";
            }
        }
        if($decenas == 6){
            if($unidades == 0){
                $Total = "";
                $Total = $Total . "sesenta ";
            }
        }
        if($decenas == 7){
            if($unidades == 0){
                $Total = "";
                $Total =$Total . "setenta ";
            }
        }
        if($decenas == 8){
            if($unidades == 0){
                $Total = "";
                $Total =$Total . "ochenta ";
            }
        }
        if($decenas == 9){
            if($unidades == 0){
                $Total = "";
                $Total =$Total . "noventa ";
            }
        }
    }


    switch($centenas){
        Case 1:
            $strce = "ciento ";break;
        Case 2:
            $strce = "doscientos ";break;
        Case 3:
            $strce = "trescientos ";break;
        Case 4:
            $strce = "cuatrocientos ";break;
        Case 5:
            $strce = "quinientos ";break;
        Case 6:
            $strce = "seiscientos ";break;
        Case 7:
            $strce = "setecientos ";break;
        Case 8:
            $strce = "ochocientos ";break;
        Case 9:
            $strce = "novecientos ";break;
    }

    $Total = $strce . $Total;
    $aux =$Total;

    if($centenas == 1){
        if($decenas == 0 && $unidades == 0){
            $Total = "cien";
            $aux =$Total;
        }
    }

    switch($miles){
        Case 1:
            $strmi = "un mil ";
        $Total = $strmi . $Total;
        break;
        Case 2:
            $strmi = "dos mil ";
        $Total = $strmi . $Total;
        break;
        Case 3:
            $strmi = "tres mil ";
        $Total = $strmi . $Total;
        break;
        Case 4:
            $strmi = "cuatro mil ";
        $Total = $strmi . $Total;
        break;
        Case 5:
            $strmi = "cinco mil ";
        $Total = $strmi . $Total;
        break;
        Case 6:
            $strmi = "seis mil ";
        $Total = $strmi . $Total;
        break;
        Case 7:
            $strmi = "siete mil ";
        $Total = $strmi . $Total;
        break;
        Case 8:
            $strmi = "ocho mil ";
        $Total = $strmi . $Total;
        break;
        Case 9:
            $strmi = "nueve mil ";
        $Total = $strmi . $Total;
        break;
    }


    switch($demiles){
        Case 1:
            $strdemiles = "dieci";break;
        Case 2:
            $strdemiles = "veinti";break;
        Case 3:
            $strdemiles = "treinta y ";break;
        Case 4:
            $strdemiles = "cuarenta y ";break;
        Case 5:
            $strdemiles = "cincuenta y ";break;
        Case 6:
            $strdemiles = "sesenta y ";break;
        Case 7:
            $strdemiles = "setenta y ";break;
        Case 8:
            $strdemiles = "ochenta y ";break;
        Case 9:
            $strdemiles = "noventa y ";break;
    }
    $Total = $strdemiles . $Total;

    if($longitud >= 5){
        if($demiles == 1){
            $Total = "";
            if($miles == 1){
                $Total =$Total . "once mil " . $aux;
            }
            if($miles == 2){
                $Total =$Total . "doce mil " . $aux;
            }
            if($miles == 3){
                $Total =$Total . "trece mil " . $aux;
            }
            if($miles == 4){
                $Total =$Total . "catorce mil " . $aux;
            }
            if($miles == 5){
                $Total =$Total . "quince mil " . $aux;
            }
            //Agregue estas lineas porque no imprimia con 17000 en adelante
            if($miles == 6){
                $Total =$Total . "dieciseis mil " . $aux;
            }
            if($miles == 7){
                $Total =$Total . "diecisiete mil " . $aux;
            }
            if($miles == 8){
                $Total =$Total . "dieciocho mil " . $aux;
            }
            if($miles == 9){
                $Total =$Total . "diecinueve mil " . $aux;
            }
        }
    }

    switch($cemiles){
        Case 1:
            $strcemiles = "ciento ";break;
        Case 2:
            $strcemiles = "doscientos ";break;
        Case 3:
            $strcemiles = "trescientos ";break;
        Case 4:
            $strcemiles = "cuatrocientos ";break;
        Case 5:
            $strcemiles = "quinientos ";break;
        Case 6:
            $strcemiles = "seiscientos ";break;
        Case 7:
            $strcemiles = "setecientos ";break;
        Case 8:
            $strcemiles = "ochocientos ";break;
        Case 9:
            $strcemiles = "novecientos ";break;
    default:
        $strcemiles ="";break;
    }

    if($miles == 0){
        switch($demiles){
            Case 1:
                $strdemiles = "diez mil ";break;
            Case 2:
                $strdemiles = "veinte mil ";break;
            Case 3:
                $strdemiles = "treinta mil ";break;
            Case 4:
                $strdemiles = "cuarenta mil ";break;
            Case 5:
                $strdemiles = "cincuenta mil ";break;
            Case 6:
                $strdemiles = "sesenta mil ";break;
            Case 7:
                $strdemiles = "setenta mil ";break;
            Case 8:
                $strdemiles = "ochenta mil ";break;
            Case 9:
                $strdemiles = "noventa mil ";break;
        }
        $Total = $strcemiles . $strdemiles . $aux;
    }

    if($miles == 0 && $demiles == 0){
        switch($cemiles){
            Case 1:
                $strcemiles = "cien mil ";break;
            Case 2:
                $strcemiles = "doscientos mil ";break;
            Case 3:
                $strcemiles = "trescientos mil ";break;
            Case 4:
                $strcemiles = "cuatrocientos mil ";break;
            Case 5:
                $strcemiles = "quinientos mil ";break;
            Case 6:
                $strcemiles = "seiscientos mil ";break;
            Case 7:
                $strcemiles = "setecientos mil ";break;
            Case 8:
                $strcemiles = "ochocientos mil ";break;
            Case 9:
                $strcemiles = "novecientos mil ";break;
        default:
            $strcemiles ="";break;
        }
        $Total = $strcemiles . $aux;
    } Else {
        //comente la linea en el commit
        //$strcemiles="";	
        $Total = $strcemiles . $Total;
        //reemplace la palabra repetida
        //$Total=eregi_replace("CIENTO CIENTO","CIENTO",$Total);
        $Total=preg_replace("/CIENTO CIENTO/","CIENTO",$Total);
    }

    $aux =$Total;
    switch($millon){
        Case 1:
            $strmillon = "un millon ";break;
        Case 2:
            $strmillon = "dos millones ";break;
        Case 3:
            $strmillon = "tres millones ";break;
        Case 4:
            $strmillon = "cuatro millones ";break;
        Case 5:
            $strmillon = "cinco millones ";break;
        Case 6:
            $strmillon = "seis millones ";break;
        Case 7:
            $strmillon = "siete millones ";break;
        Case 8:
            $strmillon = "ocho millones ";break;
        Case 9:
            $strmillon = "nueve millones ";break;
    }
    $Total = $strmillon . $Total;


    switch($demillon){
        Case 1:
            $strdemillon = "dieci";break;
        Case 2:
            $strdemillon = "veinti";break;
        Case 3:
            $strdemillon = "treinta y ";break;
        Case 4:
            $strdemillon = "cuarenta y ";break;
        Case 5:
            $strdemillon = "cincuenta y ";break;
        Case 6:
            $strdemillon = "sesenta y ";break;
        Case 7:
            $strdemillon = "setenta y ";break;
        Case 8:
            $strdemillon = "ochenta y ";break;
        Case 9:
            $strdemillon = "noventa y ";break;
    }
    $Total = $strdemillon . $Total;

    if($longitud >= 8){
        if($demillon == 1){

            if($millon == 0){
                $Total = "";
                $Total =$Total . "diez millones " . $aux;
            }
            if($millon == 1){
                $Total = "";
                $Total =$Total . "once millones " . $aux;
            }
            if($millon == 2){
                $Total = "";
                $Total =$Total . "doce millones " . $aux;
            }
            if($millon == 3){
                $Total = "";
                $Total =$Total . "trece millones " . $aux;
            }
            if($millon == 4){
                $Total = "";
                $Total =$Total . "catorce millones " . $aux;
            }
            if($millon == 5){
                $Total = "";
                $Total =$Total . "quince millones " . $aux;
            }
        }
    }

    $Total = trim($Total) . $pesos;
    $convertir = $Total;
    return "(".$convertir.")";
}

function implode_wrap($before, $after, $glue, $array){
    $output = '';
    foreach($array as $item){
        $output .= $before . $item . $after . $glue;
    }
    return substr($output, 0, -strlen($glue));
}

function extractCommonWords($string, $how_many = 10){       
    $stopWords = array(
        'i','a','about','an','and','are','as','at','be','by','com','de','en',
        'for','from','how','in','is','it','la','of','on','or','that','the','this','to',
        'was','what','when','where','who','will','with','und','the','www',
        'yo','un','acerca','una','y','son','como','en','ser','por',
        'para','desde','como','end','es','esto','sobre','o','eso','el','esto',
        'fue','que','cuando','donde','como','sera','con', 'estabamos','dice', 'hasta','0', 'decia'
    );
    $string = preg_replace('/ss+/i', '', $string);
    $string = trim($string); // trim the string       
    $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too¿       
    $string = strtolower($string); // make it lowercase           
    preg_match_all('/\b.*?\b/i', $string, $matchWords);       
    $matchWords = $matchWords[0];              
    foreach ( $matchWords as $key=>$item ) {
        if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {
            unset($matchWords[$key]);           
        }       
    }          
    $wordCountArr = array();
    if ( is_array($matchWords) ) {
        foreach ( $matchWords as $key => $val ) {               
            $val = strtolower($val);               
            if ( isset($wordCountArr[$val]) ) {                   
                $wordCountArr[$val]++;               
            } else {
                $wordCountArr[$val] = 1;
            }           
        }
    }
    arsort($wordCountArr);       
    $wordCountArr = array_slice($wordCountArr, 0, $how_many);       
    return $wordCountArr;   
} 

function isset_or(&$check, $alternate = NULL){
    return (isset($check)) ? $check : $alternate;
} 

function get_css_styles($skin = 'default'){
    $css = "";
    $css .= file_get_contents("media/skin/$skin/style.css");
    $css .= file_get_contents("media/skin/$skin/containers.css");
    $css .= file_get_contents("media/skin/$skin/tabs.css");
    $css .= file_get_contents("media/skin/$skin/containers.css");
    $css .= file_get_contents("media/skin/$skin/delirium_windows.css");
    $css .= file_get_contents("media/skin/$skin/deliriumsearch.css");
    $css .= file_get_contents("media/skin/$skin/delirium_suggest.css");
    //$css .= file_get_contents("media/skin/$skin/liquidcorners.css");
    //$css .= file_get_contents("media/skin/$skin/forms.css");
    //$css .= file_get_contents("media/skin/$skin/buttons.css");
    $css .= file_get_contents("media/skin/$skin/msg.css");
    return $css;
}


//http://php.net/manual/en/ref.network.php
function is_ip_in_cidir($ip, $cidir) {
    //echo "NET MASK $net_mask ";
    list ($net, $mask) = explode ("/", $cidir);
    $mask = $mask == "" ? 255: $mask;
    echo "NET $net MASK $mask";
    
    $ip = ip2long($ip);
    $rede = ip2long($net);
    $mask = ~((1 << (32 - $mask)) - 1);
    //echo "ip $ip<br/>";
    //echo "MASK $mask<br/>";
    
    //AND
    $res = $ip & $mask;
    
    //echo "[$ip & $mask] $res  == $rede<br/>";
    return ($res == $rede);
  }

function is_ip_in_range($ip,$range){
    // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
    if (strpos($range, '*') !==false) { // a.b.*.* format
      // Just convert to A-B format by setting * to 0 for A and 255 for B
      $lower = str_replace('*', '0', $range);
      $upper = str_replace('*', '255', $range);
      $range = "$lower-$upper";
    }

    if (strpos($range, '-')!==false) { // A-B format
      list($lower, $upper) = explode('-', $range, 2);
      $lower_dec = (float)sprintf("%u",ip2long($lower));
      $upper_dec = (float)sprintf("%u",ip2long($upper));
      $ip_dec = (float)sprintf("%u",ip2long($ip));
      return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
    }

    if($ip == $range){
        return true;
    }else{
        return false;
    }

    echo "$ip - $range Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format<br>";
    return false;
}

function getIpAddress() {

$ip = '';

if($_SERVER) {
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
$ip = $_SERVER['HTTP_CLIENT_IP'];
}else{
$ip = $_SERVER['REMOTE_ADDR'];
}
} else {
if(getenv('HTTP_X_FORWARDED_FOR')){
$ip = getenv('HTTP_X_FORWARDED_FOR');
}elseif(getenv('HTTP_CLIENT_IP')){
$ip = getenv('HTTP_CLIENT_IP');
}else{
$ip = getenv('REMOTE_ADDR');
}
}

return $ip;
}

function cargar_extra($include_file = ''){
    $include_file = PATH_CORE_EXTRA.$include_file;
    if(is_file($include_file)){
        include($include_file);
    }else{
        echo 'div class="error">Controladorchivor <i>',$include_file,'</i> no encontrado</div> ';
    }
}

function barcode($text = '', $barcodeheight = 50, $bol_mostrar_texto = 1){
    //$text  =  implode($argv, "  "); 
    $barcodethinwidth=2; 
    $barcodethickwidth=$barcodethinwidth*3; 
    $font_size = 5;
    $line_width = strlen($text) * imagefontwidth($font_size);
    $line_height= 0;
    if($bol_mostrar_texto == 1){
        $line_height = imagefontheight($font_size) ;
    }
    $codingmap  =  Array( "0"=> "000110100", "1"=> "100100001", 
        "2"=> "001100001", "3"=> "101100000", "4"=> "000110001", 
        "5"=> "100110000", "6"=> "001110000", "7"=> "000100101", 
        "8"=> "100100100", "9"=> "001100100", "A"=> "100001001", 
        "B"=> "001001001", "C"=> "101001000", "D"=> "000011001", 
        "E"=> "100011000", "F"=> "001011000", "G"=> "000001101", 
        "H"=> "100001100", "I"=> "001001100", "J"=> "000011100", 
        "K"=> "100000011", "L"=> "001000011", "M"=> "101000010", 
        "N"=> "000010011", "O"=> "100010010", "P"=> "001010010",    
        "Q"=> "000000111", "R"=> "100000110", "S"=> "001000110", 
        "T"=> "000010110", "U"=> "110000001", "V"=> "011000001", 
        "W"=> "111000000", "X"=> "010010001", "Y"=> "110010000", 
        "Z"=> "011010000", " "=> "011000100", "$"=> "010101000", 
        "%"=> "000101010", "*"=> "010010100", "+"=> "010001010", 
        "-"=> "010000101", "."=> "110000100", "/"=> "010100010"); 
    $text  =  strtoupper($text); 
    $text_original  =  $text; 
    $text  =  "*$text*";  //  add  start/stop  chars. 
    $textlen  =  strlen($text); 
    $barcodewidth  =  ($textlen)*(7*$barcodethinwidth + 3*$barcodethickwidth)-$barcodethinwidth; 
    $im  =  ImageCreate($barcodewidth,$barcodeheight); 
    $black  =  ImageColorAllocate($im,0,0,0); 
    $white  =  ImageColorAllocate($im,255,255,255); 
    imagefill($im,0,0,$white); 
    $xpos=0; 
    for  ($idx=0;$idx<$textlen;$idx++)  { 
        $char  =  substr($text,$idx,1); 
        // make  unknown  chars  a  '-'; 
        if  (!isset($codingmap[$char]))  $char  =  "-"; 
        for  ($baridx=0;$baridx<=8;$baridx++)  { 
            $elementwidth  =  (substr($codingmap[$char],$baridx,1))  ? $barcodethickwidth : $barcodethinwidth; 
            if  (($baridx+1)%2)  imagefilledrectangle($im,$xpos,0,$xpos + $elementwidth-1,$barcodeheight-$line_height,$black); 
            $xpos+=$elementwidth; 
        } 
        $xpos+=$barcodethinwidth; 
    } 
    if($bol_mostrar_texto == 1){
        $centrado = ($barcodewidth - $line_width) / 2;
        imagestring($im,$font_size,$centrado,$barcodeheight-$line_height,$text_original,$black);
    }
    Header( "Content-type:  image/gif"); 
    ImageGif($im); 
    ImageDestroy($im); 
    return;        

}

function echo_sql($strSql = ''){
    echo '<pre>', $strSql, '</pre>';
}


function sanitize_filename($title){	
        // Preserve escaped octets.
        $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
        // Remove percent signs that are not part of an octet.
        $title = str_replace('%', '', $title);
        // Restore octets.
        $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);
        $title = remove_accents($title);
        if (seems_utf8($title)) {
                if (function_exists('mb_strtolower')) {
                        $title = mb_strtolower($title, 'UTF-8');
                }
                $title = utf8_uri_encode($title, 200);
        }
        $title = strtolower($title);
        $title = preg_replace('/&.+?;/', '', $title); // kill entities
        $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
        $title = preg_replace('/\s+/', '-', $title);
        $title = preg_replace('|-+|', '-', $title);
        $title = trim($title, '-');
        return $title;
}    

function remove_accents($string) {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    if (seems_utf8($string)) {
        $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
        // Euro Sign
        chr(226).chr(130).chr(172) => 'E',
        // GBP (Pound) Sign
        chr(194).chr(163) => '');

        $string = strtr($string, $chars);
    } else {
        // Assume ISO-8859-1 if not UTF-8
        $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
            .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
            .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
            .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
            .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
            .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
            .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
            .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
            .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
            .chr(252).chr(253).chr(255);

        $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

        $string = strtr($string, $chars['in'], $chars['out']);
        $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
        $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
        $string = str_replace($double_chars['in'], $double_chars['out'], $string);
    }

    return $string;
}

function seems_utf8($Str) { # by bmorel at ssi dot fr
    $length = strlen($Str);
    for ($i=0; $i < $length; $i++) {
        if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
        elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
        elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
        elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
        elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
        elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
        else return false; # Does not match any model
        for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
            if ((++$i == $length) || ((ord($Str[$i]) & 0xC0) != 0x80))
            return false;
        }
    }
    return true;
}

function utf8_uri_encode( $utf8_string, $length = 0 ) {
    $unicode = '';
    $values = array();
    $num_octets = 1;
    $unicode_length = 0;

    $string_length = strlen( $utf8_string );
    for ($i = 0; $i < $string_length; $i++ ) {

        $value = ord( $utf8_string[ $i ] );

        if ( $value < 128 ) {
            if ( $length && ( $unicode_length >= $length ) )
                break;
            $unicode .= chr($value);
            $unicode_length++;
        } else {
            if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

            $values[] = $value;

            if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
                break;
            if ( count( $values ) == $num_octets ) {
                if ($num_octets == 3) {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
                    $unicode_length += 9;
                } else {
                    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
                    $unicode_length += 6;
                }

                $values = array();
                $num_octets = 1;
            }
        }
    }

    return $unicode;
}

function utf8_urldecode($str) {
    $str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    return html_entity_decode($str,null,'UTF-8');;
  }

?>
