<style>
	
#menu_general{
	bottom:0pt;
	display:block;
	position: absolute;
	left:0pt;
	width:125px;	
	z-index:900;
}
body > div#menu_general{
	position:fixed;
}
#menu_general ul{
	margin-bottom:2px;
	padding:0px;
}
#menu_general ul li{
	list-style-image:none;
	list-style-position:outside;;
	list-style-type:none;
	border-top:1px  solid #434340;
}
#menu_general ul li a{
	line-height:16px;
	text-decoration:none;
	display:block;
	width:200px;
}

#menu_general_opciones{
	background: #dfe8f6;
	border:1px solid #99BBE8;
}

#menu_general ul li a{
	display:block;
	width:125px;	
	padding:3px;	
}
#menu_general  ul li a span{
	display:none;
}
#menu_general-1{
	background:transparent url(images/bg-comentario-1.gif) no-repeat scroll 0pt 0pt;
}

#working_area{
    overflow:visible !important;
    padding-bottom:0px;
}

#wrapper{
    height:100%;
    overflow:auto;
    position:absolute;
    width:100%;
    margin:0px auto;
}
</style>
<script>
var timeOutMenuGeneral=null;
var mouseOver=0;
function ocultar_menu_general(){
	debug("function value:"+mouseOver);	
	if(mouseOver==0){
		$hide('menu_general_opciones');
	}
}	
window.onload=function(){
    mvcPost("core_menu","","menu",function(){
        $j('#working_area, #wrapper').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()));
    });
    $j(window).bind("resize",function(){
        $j('#working_area, #wrapper').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()));
    });
}



function verificar_alertas(){
	//mvcPost('alertas::verificar','id_usuario={id_usuario}','','mostrar_alertas');
	//clima_widget();
	//reloj_widget();
}
</script>
<div id="menu"></div>
<div id="wrapper">
    <div id="working_area" class="strip_bg"></div>
</div>
<div id="menu_general" onmouseover="mouseOver=1;" onmouseout="mouseOver=0;timeOutMenuGeneral=setTimeout('ocultar_menu_general()',1000);">	
	<ul id="menu_general_opciones" style="display:none" >		
		<li>
			<a href="#" onclick="_window({mvc:'Usuarios::view_change_passwd',title:'Cambiar contraseña'});return false;">
				%CAMBIAR_PASSWD%
			</a>
		</li>
		<li>
			<a href="#" onclick="mvcPost('usuarios::logOut','','working_area');return false;">
				%SALIR%
			</a>
		</li>
	</ul>
	<div>
		<input style="margin:0px !important;" type="button" class="btn btn_computer" onclick="$toggle('menu_general_opciones');" value="%MENU%" />
	</div>
</div>

<div id="footer">	
	<div id="notification_area"></div>
	<div id="windows_area"></div>
	<div style="float:right;">
		<ul id="widgets">
				<!--<li>
				Clima en Monterrey:<span id="clima"></span>&nbsp;&nbsp;&nbsp;&nbsp;
				</li>
				<li>
				&nbsp;&nbsp;Hora Actual<span id="reloj"></span>&nbsp;&nbsp;
				</li>
				<li>
				Lenguaje / Languaje
				<select id="_lang" onchange="mvcPost('core_lang','lang='+$value('_lang'),'notification_area');">
					<option value="es">Espa&ntilde;ol</option>
					<option value="en">English</option>
				</select>
			</li>-->			
		</ul>							
	</div>
</div> 
