<?php
$errores=array();
$mensajes=array();
$notificaciones=array();

function setError($msg,$tipo='error'){
	global $errores,$notificaciones;
	$errores[]=array('msg'=>$msg,'tipo'=>$tipo);
	$notificaciones[]=array('msg'=>$msg,'tipo'=>$tipo);
}
function setMsg($msg,$tipo=''){
	global $mensajes,$notificaciones;
	$mensajes[]=array('msg'=>$msg,'tipo'=>$tipo);
	$notificaciones[]=array('msg'=>$msg,'tipo'=>$tipo);
}

function no_errores(){
	global $errores;
	return sizeof($errores);
}


function desplegar_errores(){
	global $errores;
	$out='';
    $total_errores = sizeof($errores);
	for( $x = 0; $x < $total_errores; ++$x){
		$cssStyle='';
		switch($errores[$x]['tipo']){
			case 'error':
				$cssStyle='error';				
				break;
			case 'warning':
				$cssStyle='warning';
				break;
		}
		$out.='<div class="'.$cssStyle.'">'.$errores[$x]['msg'].'</div>';
	}
	return $out;
}

function desplegar_mensajes(){
	global $mensajes;
	$out="";
    $total_mensajes = sizeof($mensajes);
	for( $x = 0; $x < $total_mensajes; ++$x){
		$cssStyle='';
		switch($mensajes[$x]['tipo']){
			case '':
				$cssStyle='msg';				
				break;
			case 'ok':
				$cssStyle='msgok';
				break;
		}
		$out.='<div class="'.$cssStyle.'">'.$mensajes[$x]['msg'].'</div>';
	}
	return $out;
}

function notificaciones(){
	global $notificaciones;
	$out="";
    $total_notificaciones = sizeof($notificaciones);
	for( $x = 0; $x < $total_notificaciones; ++$x){
		$cssStyle='';
		switch($notificaciones[$x]['tipo']){
			case 'error':
				$cssStyle='error';				
				break;
			case 'warning':
				$cssStyle='warning';
				break;			
			case 'ok':
				$cssStyle='msgok';
				break;
			default:
				$cssStyle='msg';				
				break;
		}
		$out.="<div class='$cssStyle'>{$notificaciones[$x]['msg']}</div>";
	}
	return $out;		
}
?>
