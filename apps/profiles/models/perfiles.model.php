<?php
if(!class_exists('Model_Perfiles')){
    class Model_Perfiles extends Model_Base {		
    	var $qryMenu;
    	
    	function getMenuListGroups($id_perfil){	
    		$result=mysql_query("select * from core_cat_perfiles where id_perfil=$id_perfil ")or die(mysql_error());
    		$obj=mysql_fetch_object($result);
    		$perfil=$obj->chr_perfil;
    		
    		mysql_free_result($result);							
    		$this->qryMenu="Select m.uuid,m.titulo,if(isnull(a.id_perfil),0,1) as asignado  ".
    		"from core_cat_menu m left join core_tbl_perfiles_permisos a on a.uuid=m.uuid  and a.id_perfil=$id_perfil ";
    				
    		$result=mysql_query($this->qryMenu."where m.parent_uuid='0' order by m.orden ") or die(mysql_error());
    		
    		$x=1;
    		$btn_registrar='<div class="panel_buttons">
    			<span class="button">
    				<input type="submit" value="%REGISTRAR%"/>
    			</span>
    		</div>';
    		$menu="";
    		$menu.="<div calss='header' style=\"text-align:center;\"><b>PERMISOS ASIGNADOS AL PERFIL: $perfil</b></div>";
    		$menu.="<div class='header'>".
    		"<input type='hidden' id='id_perfil' name='id_perfil' value='$id_perfil'/>";	
    		
    		$menu.="$btn_registrar";						
    		while ($obj = mysql_fetch_object($result)) {
    			if($obj->asignado==1){
    				$checked=" checked='true' ";				
    			}else{
    				$checked=" ";
    			}
    			$menu.="<div>".
    						"<input type='checkbox' id='uuid[]' name='uuid[]' $checked value='{$obj->uuid}'>{$obj->titulo}".						
    					"</div>".											
    			$this->getSubMenuListACL($obj->uuid,1);			    			    			    		  			  	
    			$x++;
    		}				
    		$menu.="$btn_registrar";
    		
    					  			
    		return $menu;		 		
    	}	
    	
    	function getSubMenuListACL($uuid,$level){					
    		$submenu="";
    		
    		$result=mysql_query($this->qryMenu." where parent_uuid='$uuid'  order by m.orden ");				
    		$level_sep="";		
    		
    		for($i=1;$i<=$level;$i++){			
    			$level_sep.="&nbsp&nbsp&nbsp&nbsp";
    		}
    		if(mysql_num_rows($result)>0){
    			$submenu.="";
    			while ($obj = mysql_fetch_object($result)) {							
    				
    				$result2=mysql_query($this->qryMenu." where parent_uuid='{$obj->uuid}'");
    				$new_level=$level+1;
    				if(mysql_num_rows($result2)>0){
    					if($obj->asignado==1){
    						$checked=" checked='true' ";				
    					}else{
    						$checked=" ";
    					}
    					$submenu.="<div>".
    								"$level_sep<input type='checkbox' id='uuid[]' name='uuid[]' $checked value='{$obj->uuid}'>{$obj->titulo}".
    							  "</div>".	
    						$this->getSubMenuListACL($obj->uuid,($new_level));						
    				}else{
    					if($obj->asignado==1){
    						$checked=" checked='true' ";				
    					}else{
    						$checked=" ";
    					}
    					$submenu.="<div>".
    								"$level_sep<input type='checkbox' id='uuid[]' name='uuid[]' $checked value='{$obj->uuid}'>{$obj->titulo}".
    							"</div>";									
    				}				
    			}
    			$submenu.="";
    		}
    		return $submenu;
    	}
    		
    	
    	function save($datos){
    		extract($datos);
    		$strSQLRegistrarPerfil="
    			INSERT INTO core_cat_perfiles (chr_perfil,txt_comentarios) 
    			VALUES('$chr_perfil','$txt_comentarios')";
    		query($strSQLRegistrarPerfil);				
    	}	
    	
    	function get_info($id_perfil){
    		$strSQLGetInfo="
    			SELECT 
    				* 
    			FROM
    				core_cat_perfiles 
    			WHERE
    				id_perfil='$id_perfil'";
    		return query2vars($strSQLGetInfo);
    		
    	}
    	function edit($data){
    		extract($data);
    		$strSQLUpdate="
    			UPDATE 
    				core_cat_perfiles
    			SET
    				chr_perfil='$chr_perfil',
    				txt_comentarios='$txt_comentarios'
    			WHERE
    				id_perfil='$id_perfil'";
    		query($strSQLUpdate);
    	}
    	function set_acl($datos){		
    		
    		query("DELETE FROM core_tbl_perfiles_permisos WHERE id_perfil='{$datos['id_perfil']}' ");
    		if(isset($datos['uuid'])){
    			for($x=0;$x<sizeof($datos['uuid']);$x++){
    				$uuid=$datos['uuid'][$x];
    				query("insert into core_tbl_perfiles_permisos (id_perfil,uuid) values ('{$datos['id_perfil']}','$uuid')");
    			}
    		}				
    	}
    	function get_list($id_selected=0){
    		$strSQLList="
    			SELECT 
    				*,if(id_perfil='$id_selected',' selected ','') as selected
    			FROM
    				core_cat_perfiles
                order by chr_perfil";
    		return query2array($strSQLList);
    	}
    	
    }
}
?>