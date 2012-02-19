<?php

function get_session_data(){
	$refer="";
	if (isset($_SERVER['HTTP_REFERER'])) {
		$refer=$_SERVER['HTTP_REFERER'];
	}	

	if (trim(session_id())!="") {
			
		if(!session_is_registered('online')){
		    mysql_query("INSERT INTO tbl_sesiones (session_id, ultima_actividad, ip, ultima_pagina,id_user)
		    VALUES ('".session_id()."', now(), '{$_SERVER['REMOTE_ADDR']}', '$refer',0)");
		    session_register('online');
		} else {
		    if(session_is_registered('id_user')){
		        mysql_query("UPDATE tbl_sesiones SET ultima_actividad=now(),id_user={$_SESSION['id_user']}, es_miembro=1 WHERE session_id='".session_id()."'");
		    }
		}
		if(session_is_registered('online')){        
		    mysql_query("UPDATE tbl_sesiones SET ultima_actividad=now() WHERE session_id='".session_id()."'");
		    if(session_is_registered('id_user')){		    	
		        mysql_query("UPDATE tbl_sesiones SET ultima_actividad=now(),id_user={$_SESSION['id_user']}, es_miembro=1 WHERE session_id='".session_id()."'");
		    }
		} 
	}
	
	if(session_is_registered('id_user')){		    	
        mysql_query("UPDATE tbl_sesiones SET ultima_actividad=now(),id_user={$_SESSION['id_user']}, es_miembro=1 WHERE session_id='".session_id()."'") or die(mysql_error());
    }
	
	$limit_time =  time() - 600;// ini_get("session.gc_maxlifetime")-60;// time() - 300; // 5 Minute time out. 60 * 5 = 300
	$sql = mysql_query("DELETE FROM tbl_sesiones WHERE UNIX_TIMESTAMP(ultima_actividad) < '$limit_time'");
	//mysql_query($sql);
	$sql = mysql_query("SELECT * FROM tbl_sesiones WHERE UNIX_TIMESTAMP(ultima_actividad) >= $limit_time AND es_miembro=0 GROUP BY ip,session_id") or die (mysql_error());
	$sql_es_miembro = mysql_query("SELECT * FROM tbl_sesiones WHERE UNIX_TIMESTAMP(ultima_actividad) >= $limit_time AND es_miembro=1 GROUP BY ip,session_id") or die (mysql_error());
	$app['anonimos'] = " ".mysql_num_rows($sql)." ";
	$app['miembros'] = " ".mysql_num_rows($sql_es_miembro)." ";
		
	return $app;
}
function clear_sesion_data(){
	$qry="DELETE FROM tbl_sesiones WHERE session_id='".session_id()."'";
	$limit_time = time() - 600;// ini_get("session.gc_maxlifetime")-60;//time() - 300; // 5 Minute time out. 60 * 5 = 300
	$sql = mysql_query("DELETE FROM tbl_sesiones WHERE UNIX_TIMESTAMP(ultima_actividad) < '$limit_time' ");
	mysql_query($qry)  or die(mysql_error());
}
?>
