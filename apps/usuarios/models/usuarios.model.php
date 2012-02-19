<?php
if(!class_exists('Model_Usuarios')){
    class Model_Usuarios extends Model_Base{
    
    	function login($data){
    		extract($data);
    		$qryValidarUsuario="Select * from core_tbl_usuarios where chr_nombre_usuario='{$_REQUEST['chr_username']}' and LCASE(chr_passwd)=MD5(LCASE('{$_REQUEST['chr_passwd']}'))";
    		$datos=query2vars($qryValidarUsuario);
    		if (sizeof($datos)>0) {
    			foreach ($datos as $campo=>$valor){
    				$_SESSION[$campo]=$valor;
    			}
    			return true;
    		}else {
    			setError("Verifique sus datos");
    			return false;
    		}
    	}
    
    	function full_save($data){
    		extract($data);
    		$passwd=strtolower($chr_paterno);
    		$username=strtolower(substr($chr_nombres, 0,1).$chr_paterno);
    		$strSQLSave="
    			INSERT INTO
    				core_tbl_usuarios
    					(chr_nombre_usuario,chr_passwd,id_perfil,chr_nombres,chr_paterno,chr_materno)
    			VALUES
    				('$username',md5(LCASE('$passwd')),'$id_perfil','$chr_nombres','$chr_paterno','$chr_materno')";
    		query($strSQLSave);
    	}
    
    	function save($username,$passwd,$id_perfil,$chr_nombres,$chr_paterno,$chr_materno){
    
    		$strSQLSave="
    			INSERT INTO
    				core_tbl_usuarios
    					(chr_nombre_usuario,chr_passwd,id_perfil,chr_nombres,chr_paterno,chr_materno)
    			VALUES
    				('$username',md5(LCASE('$passwd')),'$id_perfil','$chr_nombres','$chr_paterno','$chr_materno')";
    		query($strSQLSave);
    	}
    	function get_info($id_usuario){
    		$strSQLGetInfo="
    			SELECT
    				*
    			FROM
    				core_tbl_usuarios
    			WHERE
    				id_usuario='$id_usuario'
    		";
    		$template=query2vars($strSQLGetInfo);
    
    		$strSQLgetPerfiles="
    			SELECT
    				p.*,if(isnull(id_usuario),' ',' selected ') selected
    			FROM
    				core_cat_perfiles p
    
    			LEFT JOIN
    				core_tbl_usuarios u on u.id_perfil=p.id_perfil and id_usuario={$_REQUEST['id_usuario']}
    				order by chr_perfil
    		";
    
    		$template['lista_perfiles']=query2array($strSQLgetPerfiles);
    		return $template;
    	}
        
        function get_info_by_username($username){
            $strSQLGetInfo="
                SELECT
                    *
                FROM
                    core_tbl_usuarios
                WHERE
                    chr_nombre_usuario='$username'
            ";
            $template=query2vars($strSQLGetInfo);
            return $template;
        }
        
    	function edit($data){
    		extract($data);
    		if(trim($new_chr_passwd)!=""){
    			$strSQLEdit="
    				UPDATE
    					core_tbl_usuarios
    				SET
    					chr_passwd=md5('$new_chr_passwd'),
    					chr_nombres='$chr_nombres',
    					chr_paterno='$chr_paterno',
    					chr_materno='$chr_materno',
    					id_perfil='$id_perfil'
    				WHERE
    					id_usuario='$id_usuario'";
    		}else{
    			$strSQLEdit="
    				UPDATE
    					core_tbl_usuarios
    				SET
    					chr_nombres='$chr_nombres',
    					chr_paterno='$chr_paterno',
    					chr_materno='$chr_materno',
    					id_perfil='$id_perfil'
    				WHERE
    					id_usuario='$id_usuario'";
    		}
    		query($strSQLEdit);
    	}
    
    	function change_passwd($data){
    		extract($data);
    		$strSQLEdit="
    				UPDATE
    					core_tbl_usuarios
    				SET
    					chr_passwd=md5('$new_chr_passwd')
    				WHERE
    					id_usuario='{$_SESSION['id_usuario']}'";
    		query($strSQLEdit);
    	}
        function remove($id_usuario){
            $strSqlRemove="
                DELETE
                FROM
                    core_tbl_usuarios
                WHERE
                    id_usuario=$id_usuario";
            query($strSqlRemove);                
        } 
        
    }
}?>