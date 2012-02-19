<div class="form">
	<fieldset>
		<legend>%DATOS_PERFIL%</legend>
		<div class="form_item">
			<label for="chr_perfil">%PERFIL%</label>
			<input type="text" id="chr_perfil" name="chr_perfil" tabindex="1" validate="noempty" value="{chr_perfil}"/>
			<input type="hidden" id="id_perfil" name="id_perfil" value="{id_perfil}" readonly />
		</div>		
		<div class="form_item">
			<label for="txt_comentarios">%COMENTARIOS%</label>
			<textarea id="txt_comentarios" name="txt_comentarios" validate="noempty" readonly>{txt_comentarios}</textarea>
		</div>
		<div class="panel_buttons">
			<span class="button">
				<input type="button" value="%CERRAR_VENTANA%" onclick="{window_id}.close();"/>
			</span>
		</div>
		
	</fieldset>
</div>