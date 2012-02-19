<?php
class Model_Seguridad extends Model_Base {

	function validar_permiso($onclick='',$id_perfil=0){
        $strSql="
            SELECT 
                count(menu.id_menu) as total 
            FROM 
                core_cat_menu AS menu
            INNER JOIN
                core_tbl_perfiles_permisos AS permisos ON menu.uuid=permisos.uuid
            WHERE
                permisos.id_perfil='$id_perfil'
                AND
                menu.onclick='$onclick'
            ";		        

        $validar=query2vars($strSql);

        if(!isset($_SESSION['id_usuario'])){
            $_SESSION['id_usuario']=0;
        }
        if($validar['total']>0||$_SESSION['id_usuario']=="1"){
        	return true;
        }else{
        	return false;
        }
	}		
}
?>
