<script>
function rebuild_menu(){
	mvcPost("core_menu","","menu");
	mvcPost("core_menu::admin_lista","parent_uuid={parent_uuid}&deep={deep}","{parent_uuid}");
	{window_id}.close();
}
</script>
<form id="form_add_menu" action="root.php" method="POST"  _do="core_menu::menu_editar" _target="menu_working_area" _callback="rebuild_menu" >
<div class="div_container">
<table style="width: auto%;font-size:10px" border="0" cellpadding="0" cellspacing="0">

  <tbody>

    <tr align="center">

      <td colspan="6" rowspan="1" class="nnheader">
      	<b>
      		Modificar Opci&oacute;n
      	</b>
      	<input type="hidden" id="parent_uuid" value="{parent_uuid}">
      	<input type="hidden" id="id_menu" value="{id_menu}">
	  </td>

    </tr>

  
	<tr>

      <td class="nnheader">UUID:</td>

      <td class="cell"><input type="text" id="uuid" value="{uuid}" ></td>
	</tr>
	
    <tr>

      <td class="nnheader">TITULO:</td>

      <td class="cell"><input type="text" id="titulo" value="{titulo}" ></td>
	</tr>

    <tr>
      <td class="nheader">link</td>
	
      <td class="cell"><input type="text"  id="href" value="{href}"  ></td>
	</tr>
	<tr>
      <td class="nheader">onclick</td>
	
      <td class="cell"><input type="text"  id="onclick" value="{onclick}"  ></td>
	</tr>	
	<tr>
      <td class="nheader">Mostrar en</td>
	
      <td class="cell">
      		<select id="en_ventana" name="en_ventana">
      			<option value="0" {selected_area_trabajo}>Area de Trabajo</option>		
				<option value="1" {selected_ventana}>Ventana</option>
      		</select>
		</td>
	</tr>
		
    <tr align="center">
      <td colspan="2" rowspan="1" class="nheader"><input class="btn btn_guardar" accesskey="r" name="submit" id="submit" value="MODIFICAR" type="submit">      
      </td>
    </tr>

  </tbody>
</table>
</div>
</form>