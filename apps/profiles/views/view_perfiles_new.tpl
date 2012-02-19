<script>
function list(){
	lista_perfiles.show();
	{window_id}.close();
}
</script>
<form id="perfil_new" class="form" method="POST" action="root.php" _do="perfiles::save" _target="" _callback="list">
	<fieldset>
		<legend>%NUEVO_PERFIL%</legend>
		<div class="form_item">
			<label for="chr_perfil">%PERFIL%</label>
			<input type="text" id="chr_perfil" name="chr_perfil" tabindex="1" validate="noempty"/>
		</div>		
		<div class="form_item">
			<label for="txt_comentarios">%COMENTARIOS%</label>
			<textarea id="txt_comentarios" name="txt_comentarios" validate="noempty" tabindex="2"></textarea>
		</div>
		<div class="panel_buttons">
			<span class="button">
				<input type="submit" value="%REGISTRAR%" tabindex="3" />
			</span>
		</div>
	</fieldset>
</form>