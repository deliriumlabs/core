<table class="search_default">
    <thead>
        <tr>
            <th colspan="2">
                {chr_subject}
            </th>
        </tr>
        <tr>
            <td>{dtm_fecha_fto}</td>
            <td>{chr_envia}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">
                <div style="height:200px; overflow-y:auto;">
                    {txt_body}
                </div>
            </td>
        </tr>
    </tbody>
</table>
<div class="panel_buttons">
    <input class="btn btn_detalle {hide_enlace}" type="button" value="Ir al enlace" onclick="{chr_enlace};{window_id}.close();"/>
    <input class="btn btn_cancelar" type="button" value="Cerrar" onclick="{window_id}.close();" />
</div>
