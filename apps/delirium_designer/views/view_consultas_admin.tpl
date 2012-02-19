<script type="text/javascript" >
    function guardar_consulta(){
        showWaitWindow('%LBL_ESPERE%');
        rpt_sql[$value('alias')] = [];
        rpt_sql[$value('alias')]['sql'] = $value('sql');
        rpt_sql[$value('alias')]['fields'] = [];

        mvcPost('DeliriumDesigner::get_fields', 'sql='+encode(rpt_sql[$value('alias')]['sql']), '',function(response){
            try{
                rpt_sql[$('alias').value]['fields'] = response.split(',');
            }catch(e){
                alert(e);
            }
            {window_id}.close();
            hideWaitWindow();
        });
    }
    
    function cargar_consulta(id){
        $('alias').value = id;

        $('sql').value = '';
        $('sql').value = rpt_sql[id]['sql'];
    }
</script>

<div id="sql_alias">
    <select onchange="cargar_consulta(this.value);">
        <option value=''>%SELECCIONE%</option>
        {{START lista_alias}}
        <option value='[alias]'>[alias]</option>
        {{END lista_alias}}
    </select>
</div>
<div id="sql_area">
    <label for="alias">Alias:</label>
    <input type="text" id="alias" name="alias" value="" /><br />
    <label for="sql">SQL</label>
    <textarea id="sql" style="width:98%;height:200px"></textarea>
</div>
<div>
    <input type="button" class="btn btn_agregar" value="Nueva Consulta" />
    <input type="button" class="btn btn_guardar" value="Guardar Consulta" onclick="guardar_consulta();" />
</div>
