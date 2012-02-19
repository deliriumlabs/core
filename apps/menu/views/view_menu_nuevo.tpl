<script>
function rebuild_menu(){
	mvcPost("core_menu","","menu");
	mvcPost("core_menu::admin_lista","parent_uuid={parent_uuid}&deep={deep}","{parent_uuid}");
	{window_id}.close();
}
</script>
<form id="form_add_menu" action="root.php" method="POST"  _do="core_menu::menu_nuevo" _target="menu_working_area" _callback="rebuild_menu" >
<div class="div_container">
<table style="width: auto%;font-size:10px" border="0" cellpadding="0" cellspacing="0">

  <tbody>

    <tr align="center">

      <td colspan="6" rowspan="1" class="nnheader">
      	<b>
      		Registrar Opci&oacute;n
      	</b>
      	<input type="hidden" id="parent_uuid" value="{parent_uuid}">
	  </td>

    </tr>

	<tr>

      <td class="nnheader">UUID:</td>

      <td class="cell"><input type="text" id="uuid" ></td>
	</tr>
	
    <tr>

      <td class="nnheader">TITULO:</td>

      <td class="cell"><input type="text" id="titulo" ></td>
	</tr>

    <tr>
      <td class="nheader">link</td>
	
      <td class="cell"><input type="text"  id="href"  ></td>
	</tr>
	<tr>
      <td class="nheader">onclick</td>
	
      <td class="cell"><input type="text"  id="onclick"  ></td>
	</tr>
	<tr>
      <td class="nheader">Mostrar en</td>
	
      <td class="cell">
      		<select id="en_ventana" name="en_ventana">
      			<option value="0">Area de Trabajo</option>		
				<option value="1">Ventana</option>
      		</select>
		</td>
	</tr>	
    <tr align="center">
      <td colspan="2" rowspan="1" class="nheader">      		
      	<input class="btn btn_guardar" accesskey="r" name="submit" id="submit" value="REGISTRAR" type="submit">	
      </td>
    </tr>

  </tbody>
</table>
</div>
</form>