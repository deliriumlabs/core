<?php
if(!class_exists('Model_System')){
    class Model_System extends Model_Base 
    {	

        function list_installers(){
            $_modulo=array();		
            $files=$this->searchRecurse(PATH_APPS,"install.php");
            for($x=0;$x<sizeof($files);$x++){
                include($files[$x]);			
            }				

            $files=$this->searchRecurse(PATH_CORE_APPS,"install.php");
            for($x=0;$x<sizeof($files);$x++){
                include($files[$x]);			
            }				

            $strSQLModulos="
                SELECT
                *,
                @rownum:=@rownum+1 AS numero_fila,
                if(@rownum % 2 = 0,'odd','') as _row_class_,
                REPLACE(REPLACE(LOWER(titulo), 'modulo de',''),'modulo', '') as ord_titulo
                FROM
                    (SELECT @rownum:=0) AS r,             
                    core_cat_modulos modulos
                    ORDER BY ord_titulo
                ";
            //return query2array($strSQLModulos);
            $modulos_registrados = query2array($strSQLModulos);
            $total_modulos_registrados = sizeof($modulos_registrados);
            //debug_array($_modulo);
            $i = 0; while ($i < $total_modulos_registrados) {
                $modulo = $modulos_registrados[$i];

                if($modulo['uuid'] == isset_or($_modulo['modulo_'.$modulo['uuid']]['uuid'], '')){
                    $test = $_modulo['modulo_'.$modulo['uuid']];
                    if($modulo['version'] != $test['version'] || $modulo['ultima_revision'] != $test['ultima_revision'] ){
                        $modulos_registrados[$i]['_row_class_'] = 'modulo_actualizado';
                    }
                }
                ++$i;
            }
            return $modulos_registrados;

        }

        function list_new_installers(){
            $_modulo=array();		
            $files=$this->searchRecurse(PATH_APPS,"install.php");
            for($x=0;$x<sizeof($files);$x++){
                include($files[$x]);			
            }				

            $files=$this->searchRecurse(PATH_CORE_APPS,"install.php");
            for($x=0;$x<sizeof($files);$x++){
                include($files[$x]);			
            }				

            $strSQLModulos="
                SELECT
                uuid
                FROM
                core_cat_modulos
                ";

            $modulos_registrados=query2ArrayRow($strSQLModulos);

            $return_array=array();		
            $x = 0;
            foreach($_modulo as $key=>$value) {
                if(isset($value['uuid']) && !in_array($value['uuid'],$modulos_registrados)){
                    $value['_row_class_'] = 'odd';
                    if( $x%2 == 0 ) $value['_row_class_'] = '';
                    $return_array[]=$value;
                    $x++;
                }
            }
            return $return_array;

        }

        function searchRecurse($dirName,$pattern,$more=true) {
            $files=array();
            if(!is_dir($dirName)){
                return false;
            }
            $dirHandle = opendir($dirName);
            while(false !== ($incFile = readdir($dirHandle))) {   		
                if(filetype("$dirName/$incFile")=="file"){	
                    $file_name=$dirName.$incFile;				
                    if(stristr(strtolower($file_name),$pattern)){
                        $files[]=$file_name;					
                        //include_once($file_name);
                    }									
                }elseif($incFile!="."&&$incFile!=".."&&$incFile!=".svn"&&filetype("$dirName/$incFile")=="dir"){
                    if($more && $incFile!="views"){						
                        $files=array_merge($files,$this->searchRecurse($dirName.$incFile.DIRSEP,$pattern,true));				
                    }
                }		
            }	   
            closedir($dirHandle);
            return $files;
        }

        function registrar_modulo($data){
            extract($data);

            $strSQLRegistrar="
                INSERT INTO 
                core_cat_modulos 
                (titulo,autor,version,ultima_revision,info,uuid)
                VALUES
                ('$titulo','$autor','$version','$ultima_revision','$info','$uuid')";
            query($strSQLRegistrar);
            echo_sql($strSQLRegistrar);
        }

        function actualizar_modulo($data){
            $titulo = '';
            $autor = '';
            $version = '';
            $ultima_version = '';
            $info = '';
            extract($data);
            $strSql = "
                UPDATE
                    core_cat_modulos 
                SET
                    titulo = '$titulo',
                    autor = '$autor',
                    version = '$version',
                    ultima_revision = '$ultima_revision',
                    info = '$info'
                WHERE
                    uuid = '$uuid'
                ";
            query($strSql);
        }

        function descomprimir_modulo(){		

            if(sizeof($_FILES)>0){
                $archivo = $_FILES["modulo"]["tmp_name"];
                if ($archivo != "none"&&strlen($archivo)>0) {
                    $zip = new ZipArchive;
                    $zip->open($archivo);
                    $zip->extractTo(PATH_APPS);
                    $zip->close();
                    setMsg("Modulo desempaquetado exitosamente"); 
                }
            }else{
                setError("No se recibio ningun archivo");
            }
            return notificaciones();
        }

        function modulo_instalado($uuid){
            $strSQLModulos="
                SELECT
                *
                FROM
                core_cat_modulos
                WHERE
                uuid = '$uuid'
                ";
            if( sizeof(query2array($strSQLModulos)) > 0 ){
                return true;
            }else{
                return false;
            }
        }
    }
}
?>
