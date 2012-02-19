<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="undefined">
<head>
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="-1" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<title>{_TITULO_APLICACION_}</title>

<!--Archivo para el deliriumkit-->

<script type="text/javascript" src="raw.php?do=system::get_js_core&timestamp={timestamp}" ></script>
<script type="text/javascript">	
	delirium_set_path('core/deliriumkit/');
	delirium_init();
	var dk= new deliriumkit();	
</script>
<script type="text/javascript">
window.onload=function(){
	mvcPost('index::validar_sesion','','body');	
}
</script>

<!--Archivo para el estilo de la aplicacion-->
<link href="media/skin/default/style.css" rel="stylesheet" type="text/css" />

<!--Archivo para el calendario-->
<link rel="stylesheet" type="text/css" media="all" href="extra/jscalendar/skins/aqua/theme.css" title="Aqua" />
<script type="text/javascript" src="extra/jscalendar/calendar.js"></script>
<script type="text/javascript" src="extra/jscalendar/lang/calendar-es.js"></script>
<script type="text/javascript" src="extra/jscalendar/calendar-setup.js"></script>

<!-- Archivos para el menu-->
<link rel="stylesheet" type="text/css" href="extra/menu/delirium/delirium.css" />
<script type="text/javascript" src="extra/menu/jsdomenu.js"></script>
<script type="text/javascript" src="extra/menu/jsdomenubar.js"></script>
<script type="text/javascript" src="extra/menu/jsdomenu.inc.js"></script>

<!-- Jquery files -->
<link type="text/css" href="extra/jquery-ui/css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="extra/jquery-ui/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="extra/jquery-ui/js/jquery-ui-1.7.1.custom.min.js"></script>
<link type="text/css" href="extra/jquery-ui/js/jquery.wysiwyg.css" rel="stylesheet" />
<script type="text/javascript" src="extra/jquery-ui/js/jquery.wysiwyg.js"></script>
<link type="text/css" href="extra/jquery-ui/css/jquery.checkbox.css" rel="stylesheet" />
<script type="text/javascript" src="extra/jquery-ui/js/jquery.checkbox.js"></script>
<link type="text/css" href="extra/jquery-ui/css/jqtransform.css" rel="stylesheet" />
<script type="text/javascript" src="extra/jquery-ui/js/jquery.jqtransform.js"></script>
<script type="text/javascript" src="extra/jquery-ui/js/jquery.offset.js"></script>

<link type="text/css" href="extra/jquery-ui/fullcalendar-1.4.1/redmond/theme.css" rel="stylesheet" />
<link type="text/css" href="extra/jquery-ui/fullcalendar-1.4.1/fullcalendar.css" rel="stylesheet" />
<script type="text/javascript" src="extra/jquery-ui/fullcalendar-1.4.1/fullcalendar.js"></script>

<link type="text/css" href="extra/jquery-ui/css/colorpicker.css" rel="stylesheet" />
<script type="text/javascript" src="extra/jquery-ui/js/colorpicker.js"></script>

<script type="text/javascript">
jQuery.noConflict();
$j = jQuery.noConflict();

</script>
<!--archivo con javascript del usuario-->
<script type="text/javascript" src="extra/funciones_usuario.js"></script>
</head>
<body bgcolor="#FFFFFF" id="body">
</body>
</html>
