<script>
function list(){
	lista_usuarios.refresh();
	{window_id}.close();
}
</script>
<form id="usuarios_new" class="form" method="POST" action="raw.php" _do="usuarios::save" _target="" _callback="list">
	<fieldset>
		<legend>%NUEVO%</legend>
		<div class="form_item">
			<label for="chr_nombres">%NOMBRES%</label>
			<input type="text" id="chr_nombres" name="chr_nombres" tabindex="1" validate="noempty"/>
		</div>
		<div class="form_item">
			<label for="paertno">%PATERNO%</label>
			<input type="text" id="chr_paterno" name="chr_paterno" tabindex="2" validate="noempty"/>
		</div>
		<div class="form_item">
			<label for="chr_materno">%MATERNO%</label>
			<input type="text" id="chr_materno" name="chr_materno" tabindex="3" validate="noempty"/>
		</div>
		<div class="form_item">
			<label for="id_perfil">%PERFIL%</label>
			<select id="id_perfil" validate="noidzero" tabindex="4" >
				<option value="0">%SELECCIONE_UN_PERFIL%</option>
				{{START lista_perfiles}}
					<option value="[id_perfil]">[chr_perfil]</option>
				{{END lista_perfiles}}
			</select>
		</div>
		<div class="panel_buttons">
			<span class="button">
				<input type="submit" value="%REGISTRAR%" tabindex="5" />
			</span>
		</div>
	</fieldset>
</form>