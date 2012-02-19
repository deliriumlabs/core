<script>
window.onload=function(){
	{window_id}.setTitle('%PASSWD%');
	{window_id}.resize(400,200);
}
function list(response){
	alert('%PASSWD_ACTUALIZADO%');
	{window_id}.close();
}
</script>
<form id="usuarios_cambiar_passwd" class="form" method="POST" action="raw.php" _do="usuarios::change_passwd" _target="" _callback="list">
	<fieldset>
		<legend>%MODIFICAR_PASSWD%</legend>
		<div class="form_item">
			<label for="new_chr_passwd">%NUEVO_PASSWORD%</label>
			<input type="text" id="new_chr_passwd" name="new_chr_passwd" tabindex="5" validate="noempty"  />
		</div>
		<div class="form_item">
			<label for="new_chr_passwd">%CONFIRMAR_PASSWD%</label>
			<input type="text" id="confirm_new_chr_passwd" name="confirm_new_chr_passwd" tabindex="5" validate="eq,$value('new_chr_passwd')"  />
		</div>
		<div class="panel_buttons">
            <div class="panel_buttons">
                <input class="btn btn_guardar" type="submit" value="%BTN_MODIFICAR%"/>
                <input class="btn btn_cancelar" type="button" value="%BTN_CANCELAR%" onclick="{window_id}.close();" />
            </div>
		</div>
	</fieldset>
</form>
