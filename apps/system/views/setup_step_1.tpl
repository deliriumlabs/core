<div id="header-setup-1" style="text-align:center">
    %HEADER_SETUP_1%
</div>
<form id="setup_step_1" method="POST" action="raw.php" _do="System::setup_do" _target="setup-wrapper">
    <div class="form_item">
        {msg}
    </div>
    <table class="search_default" cellspacing="0">
        <tr>
            <th class="header" width="200px;">
                <label>%HOSTNAME%</label>
            </th>
            <td>
                <input type="text" id="db_hostname" name="db_hostname" validate="noempty" value="{db_hostname}" />
            </td>
        </tr>
        <tr>
            <th class="header">
                <label>%DBNAME%</label>
            </th>
            <td>
                <input type="text" id="db_name" name="db_name" validate="noempty" value="{db_name}" />
            </td>
        </tr>
        <tr>
            <th class="header">
                <label>%USERNAME%</label>
            </th>
            <td>
                <input type="text" id="db_username" name="db_username" value="{db_username}" validate="noempty" />
            </td>
        </tr>
        <tr>
            <th class="header">
                <label>%PASSWD%</label>
            </th>
            <td>
                <input type="text" id="db_passwd" name="db_passwd" value="{db_passwd}" />
            </td>
        </tr>
        <tr>
            <th class="header">
                <label>%NOMBRE_APLICACION%</label>
            </th>
            <td>
                <input type="text" id="app_title" name="app_title" value="{app_title}" validate="noempty" />
            </td>
        </tr>
        <tr>
            <th class="header">
                <label>%BOOL_CREAR_DB%</label>
            </th>
            <td>
                <input type="checkbox" id="bol_crear_db" name="bol_crear_db" value="true" checked="true" />
            </td>
        </tr>
    </table>
    <div class="panel_buttons">
        <span class="button">
            <input type="submit" value="%INSTALAR%" />
        </span>
    </div>
</form>
