<script>
	window.onload=function(){
		mvcPost('System::admin_modulos','','control_panel_work');
	}
</script>
<style type="text/css" media="screen">
    .modulo_actualizado td {
        background-color:#D2E5F9 !important;
        color:#000;
    }
</style>
<div class="panel_buttons">
	<span class="button">
		<input type="button" value="Registrar Modulos" onclick="mvcPost('System::admin_modulos_instalar','','control_panel_work')" />
	</span>
    <span class="button">
		<input type="button" value="Modulos" onclick="mvcPost('System::admin_modulos','','control_panel_work')" />
	</span>
</div>
<div id="control_panel_work"></div>
<br />
<br />
<br />
<br />
<br />
