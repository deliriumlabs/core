<script>
function reset(x){
	_infoWindow("Info",x,true,{
		height: 400
	});
	mvcPost('System::admin_modulos_instalar','','control_panel_work');	
	{window_id}.close();
	hideWaitWindow();
}
</script>
<form id="upload_modulo" name="upload_modulo" method="POST" action="index.php?do=system::admin_modulos_upload" enctype="multipart/form-data" _do="system::admin_modulos_upload"  _onstart="showWaitWindow('Subiendo Modulo','Subiendo modulo espera un momento porfavor')" _callback="reset" >
	<fieldset>
		<legend>Subir Modulo</legend>
		<label for="modulo">Selecciona el archivo:</label>
		<input type="file" value="Seleccionar Archivo" name="modulo" id="modulo" validate="noempty"/><br>
		<div class="panel_buttons">
		<input type="submit" value="Subir Modulo" />
		</div>
	</fieldset>
</form>
