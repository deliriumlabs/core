<script>
function list(){
	lista_usuarios.refresh();
	{window_id}.close();
}
</script>
<form id="usuarios_edit" class="form" method="POST" action="raw.php" _do="usuarios::edit" _target="" _callback="list">
	<fieldset>
		<legend>%MODIFICAR%</legend>
		<div class="form_item">
			<label for="chr_nombres">%NOMBRES%</label>
			<input type="text" id="chr_nombres" name="chr_nombres" tabindex="1" validate="noempty"  value="{chr_nombres}"  />
			<input type="hidden" id="id_usuario" name="id_usuario" value="{id_usuario}" />
		</div>
		<div class="form_item">
			<label for="paertno">%PATERNO%</label>
			<input type="text" id="chr_paterno" name="chr_paterno" tabindex="2" validate="noempty"  value="{chr_paterno}"  />
		</div>
		<div class="form_item">
			<label for="chr_materno">%MATERNO%</label>
			<input type="text" id="chr_materno" name="chr_materno" tabindex="3" validate="noempty"  value="{chr_materno}"  />
		</div>
		<div class="form_item">
			<label for="new_chr_passwd">%NUEVO_PASSWORD%</label>
			<input type="text" id="new_chr_passwd" name="new_chr_passwd" tabindex="5" />
		</div>
		<div class="form_item">
			<label for="id_perfil">%PERFIL%</label>
			<select id="id_perfil" validate="noidzero" tabindex="4" >
				<option value="0">%SELECCIONE_UN_PERFIL%</option>
				{{START lista_perfiles}}
					<option value="[id_perfil]" [selected]>[chr_perfil]</option>
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
