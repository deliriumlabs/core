
<script>

var core_modulos=parseJson('{core_modulos}');
window.onload=function(){	
	setup_modulo("1| ");
}

function setup_modulo(response){
    debug(core_modulos);
	if (typeof(core_modulos)=="undefined") {
		return false;
	}	
	response=response.split("|");
	id=trim(response[0]);
	$('msg').innerHTML+=msg=response[1];
	hideWaitWindow();	
	if(id<=core_modulos.total){
		showWaitWindow('Creando :' + core_modulos['modulo'][id]['label']);	
		mvcPost('System::setup_modulo','nid='+id+'&id='+core_modulos['modulo'][id]['id'],'','setup_modulo');
	}
}

</script>

<div id="dummy"></div>

<div id="header-setup-1">
	%HEADER_SETUP_1%
</div>
<div id="msg">
{msg}
</div>
<!--form class="form" id="setup_step_1" method="POST" action="raw.php" _do="index" _target="setup-wrapper">
	<input type="hidden" id="db_hostname" name="db_hostname" validate="noempty" value="{db_hostname}" />
	<input type="hidden" id="db_name" name="db_name" validate="noempty" value="{db_name}" />
	<input type="hidden" id="db_username" name="db_username" value="{db_username}" validate="noempty" />
	<input type="hidden" id="db_passwd" name="db_passwd" value="{db_passwd}" validate="noempty" />
	<div class="panel_buttons">
		<span class="button">
			<input type="submit" value="%CARGAR_APLICACION%" />
		</span>
	</div>
</form-->
<span class="button">
	<input type="button" onclick="window.location.href=window.location.href" value="%CARGAR_APLICACION%" />
</span>
{ejemplo}
