<?php
if(!class_exists('Model_System')){
    class Model_System extends Model_Base 
    {	

        function list_installers(){
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
            return query2array($strSQLModulos);

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
