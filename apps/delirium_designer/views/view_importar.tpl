<script type="text/javascript">
    function view_rpt(){
        mvcPost('DeliriumDesigner::listado', '', 'working_area');
        alert('%REPORTE_IMPORTADO%');
		hideWaitWindow();
        {window_id}.close();
    }

    function importar(){
        if(!validateDiv('rpt')){
            return false;
        }
        return true;
    }
</script>
<form id="rpt_import" name="rpt_import" class="form container" method="POST" enctype="multipart/form-data" action="raw.php?do=DeliriumDesigner::import" _do="DeliriumDesigner::save"  _callback="view_rpt" _onstart="showWaitWindow('%LBL_ESPERE%...','%LBL_ESPERE%')" >
	<div id="rpt">
		<table width="100%" class="internal">				
			<tr>
				<td>			
					<label for="blb_rpt">%SELECCIONE_EL_ARCHIVO%</label>
					<input type="file" id="blb_rpt" name="blb_rpt" validate="noempty" />
				</td>
			</tr>
		</table>
	</div>
    <div class="panel_buttons">
        <input type="submit" class="btn btn_guardar" value="%BTN_IMPORTAR%" onclick="return importar();" />
    </div>
</form>
