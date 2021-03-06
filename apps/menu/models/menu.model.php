<?php

if (!class_exists('Model_Menu')) {
    
    class Model_Menu extends Model_Base
    {
        private $qryMenu;
        private $menu_array_single = array();
        private $multilang;
        
        function generar_menu($parent_uuid = 0)
        {
            $this->multilang = 0;
            /*Revisar si estan unstalados los campos para otros lenguajes*/
            $strSqlValidarColumna = "
                SHOW COLUMNS FROM 
                    core_cat_menu
                WHERE 
                    Field='titulo_en'";
            
            if (sizeof(query2array($strSqlValidarColumna)) > 0) {
                $this->multilang = 1;
            }

            /*Campo default espa�ol*/
            $campo_titulo = 'titulo AS titulo ';

            /*
            Si estan instalados los campos para otros lenguajes
            detectar el lenguaje de sesion para obtener el campo correspondiente
            */
            if($this->multilang == 1){
                $lang = isset_or($_SESSION['lang'], 'es');
                $campo_titulo = 'titulo AS titulo ';

                if($lang != 'es'){
                    $campo_titulo = 'titulo_'.$lang.' AS titulo ';
                }
            }
            
            if ($_SESSION['chr_nombre_usuario'] == 'root') {
                $this->qryMenu = "
                    SELECT 
                        id_menu, uuid, href, onclick, parent_uuid, 
                        orden, habilitado, en_ventana , $campo_titulo
                    FROM 
                        core_cat_menu ";

                $config_menu = 'addMenuItem(new menuItem("Configuracion", "configuracion"));';
                $config_menu_startup = 'core_menu_configuracion = new jsDOMenu(150, "absolute");				
                //core_menu_configuracion.addMenuItem(new menuItem("Menu", "menu", "code:mvcPost(\'core_menu::admin\',\'\',\'working_area\');"));		
                //core_menu_configuracion.addMenuItem(new menuItem("Perfiles", "perfiles", "code:mvcPost(\'Perfiles\',\'\',\'working_area\');"));
                //core_menu.items.configuracion.setSubMenu(core_menu_configuracion);	
                //core_menu_configuracion.addMenuItem(new menuItem("Control Panel...", "control_panel", "code:mvcPost(\'system::control_panel\',\'\',\'working_area\');"));
                ';
            } else {
                $result = mysql_query("SELECT id_perfil FROM core_tbl_usuarios WHERE id_usuario={$_SESSION['id_usuario']}");
                $obj = mysql_fetch_object($result);
                $id_perfil = $obj->id_perfil;
                $this->qryMenu = "
                    SELECT 
                        m.id_menu, m.uuid, m.href, m.onclick, m.parent_uuid, 
                        m.orden, m.habilitado, m.en_ventana , $campo_titulo
                    FROM core_cat_menu m 
                    INNER JOIN core_tbl_perfiles_permisos a 
                        ON a.uuid=m.uuid  AND a.id_perfil=$id_perfil ";
                $config_menu = '';
                $config_menu_startup = '';
            }
            $result = mysql_query($this->qryMenu . " WHERE parent_uuid='$parent_uuid' AND habilitado=1 ORDER BY orden ");
            $x = 0;
            $menu = 'core_menu = new jsDOMenu(150, "absolute");
            with (core_menu) {' . $config_menu . '//addMenuItem(new menuItem("Ayuda", "help", "code:_window({mvc:\'agenda::ayuda\',title:\'Ayuda sobre el sistema\'});"));
            //addMenuItem(new menuItem("Acerca de...", "about", "code:_window({mvc:\'agenda::acerca\',title:\'Acerca de ...\'});"));		    
            //addMenuItem(new menuItem("-"));
            //addMenuItem(new menuItem("Salir", "exit", "code:mvcPost(\'usuarios::logOut\');"));
        }' . $config_menu_startup;
            $titulos = array();
            $titulos_code = array();
            while ($obj = mysql_fetch_object($result)) {
                $menu.= "\n\rmainMenu$x = new jsDOMenu(150, 'absolute');\n\r" . $this->generar_sub_menu($obj->uuid, $x);
                $titulos[$x] = $obj->titulo;
                
                if ($obj->onclick != "") {
                    
                    if ($obj->en_ventana == "0") {
                        $code = '"code:mvcPost(\'' . $obj->onclick . '\',\'\',\'working_area\');"';
                    } else {
                        $code = '"code:_window({mvc:\'' . $obj->onclick . '\'});"';
                    }
                } elseif ($obj->href != "") {
                    $code = '""';
                } else {
                    $code = '""';
                }
                $titulos_code[$x] = $code;
                $x++;
            }
            $menu.= "\n\r
            absoluteMenuBar = new jsDOMenuBar('static', 'staticMenuBar');\n\r" . "with (absoluteMenuBar) {\n\r" . "	//addMenuBarItem(new menuBarItem('Sistema', core_menu,'item1'));\n\r";
            for ($j = 0; $j < $x; $j++) {

                /*
                $menu.="var mbi=new menuBarItem();\n\r";
                $menu.="mbi.displayText='{$titulos[$j]}';\n\r";
                $menu.="mbi.menuObj=mainMenu$j;\n\r";
                $menu.="mbi.actionOnClick={$titulos_code[$j]};\n\r";
                $menu.="addMenuBarItem(mbi);\n\r";
                */
                $menu.= "addMenuBarItem(new menuBarItem('{$titulos[$j]}', mainMenu$j));\n\r";
            }
            $menu.= "moveTo(0, 0);\n\r
        }\n\r
        //absoluteMenuBar.items.item1.showIcon('system', 'system', 'system');\n\r
        //core_menu.items.help.showIcon('help', 'help', 'help');\n\r
        //core_menu.items.about.showIcon('about', 'about', 'about');\n\r
        //core_menu.items.exit.showIcon('exit', 'exit', 'exit');\n\r								
        ";
            
            return $menu;
        }
        
        function generar_sub_menu($uuid, $x)
        {
            $submenu = "";
            $result = mysql_query($this->qryMenu . " WHERE parent_uuid='$uuid'  AND habilitado=1   ORDER BY orden ");
            $z = 1;
            while ($obj = mysql_fetch_object($result)) {
                $result2 = mysql_query($this->qryMenu . " WHERE parent_uuid='{$obj->uuid}'   AND habilitado=1 ORDER BY orden ");
                
                if (mysql_num_rows($result2) > 0) {
                    $sub = $x . "_" . $z;
                    
                    if ($obj->onclick != "") {
                        
                        if ($obj->en_ventana == "0") {
                            $code = '"code:mvcPost(\'' . $obj->onclick . '\',\'\',\'working_area\');"';
                        } else {
                            $code = '"code:_window({mvc:\'' . $obj->onclick . '\'});"';
                        }
                    } elseif ($obj->href != "") {
                        $code = '""';
                    } else {
                        $code = '""';
                    }

                    //$code="";
                    $submenu.= "mainMenu$sub = new jsDOMenu(150, 'absolute');\n\r";
                    $submenu.= "mainMenu$x.addMenuItem(new menuItem('{$obj->titulo}', 'item$sub', $code));\n\r";
                    $submenu.= $this->generar_sub_menu($obj->uuid, $sub);
                    $submenu.= "mainMenu$x.items.item$sub.setSubMenu(mainMenu$sub);\n\r";
                } else {
                    
                    if ($obj->onclick != "") {
                        
                        if ($obj->en_ventana == "0") {
                            $code = '"code:mvcPost(\'' . $obj->onclick . '\',\'\',\'working_area\');"';
                        } else {
                            $code = '"code:_window({mvc:\'' . $obj->onclick . '\'});"';
                        }
                    } elseif ($obj->href != "") {
                        $code = '""';
                    } else {
                        $code = '""';
                    }

                    //$code='"code:_window('."'ajax.php?{$obj->href}',{title:'{$obj->text}',width:'900',height:'450'}".');"';
                    //$code="";
                    $submenu.= "mainMenu$x.addMenuItem(new menuItem('{$obj->titulo}', '', $code));\n\r";
                }
                $z++;
            }
            
            return $submenu;
        }
        
        function obtener_submenus($parent_uuid = 0, $deep = 0)
        {
            $deep++;
            $espacio = "";
            for ($x = 1; $x < $deep; $x++) {

                //$espacio.="&nbsp;&nbsp;&nbsp;&nbsp;";
                
            }
            
            return query2array("SELECT *,'$espacio' AS espacio,'$deep' deep,if(onclick<>'','hide','show') AS container FROM core_cat_menu WHERE parent_uuid='$parent_uuid' ORDER BY orden");

        }
        
        function registrar_menu($data)
        {
            $en_ventana = 0;
            extract($data);
            
            if (isset_or($orden, '') == '') {
                $orden = "next_menu_order('$parent_uuid')";
            }
            $strSQLNuevoMenu = "
                INSERT INTO core_cat_menu (titulo,href,onclick,parent_uuid,orden,uuid,en_ventana)
                VALUES('$titulo','$href','$onclick','$parent_uuid',$orden,'$uuid','$en_ventana')";
            
            if ($this->validar_menu_uuid($uuid)) {
                query($strSQLNuevoMenu);

            }
        }
        
        function validar_menu_uuid($uuid)
        {
            $strSQLValidarMenuUUID = "
                SELECT 
                uuid, id_menu
                FROM
                core_cat_menu
                WHERE
                uuid='$uuid'";
            
            $menu = query2array($strSQLValidarMenuUUID);
            if (sizeof($menu) > 0) {
                return $menu[0]['id_menu'];
            }
            
            return true;
        }
        
        function menu_info($uuid)
        {
            $strSQLMenuInfo = "
                SELECT 
                *,
                if(en_ventana=0,' selected ','') AS selected_area_trabajo, 
                    if(en_ventana=1,' selected ','') AS selected_ventana
                        FROM 
                        core_cat_menu 
                        WHERE 
                        uuid='$uuid'";
            
            return query2vars($strSQLMenuInfo);
        }
        
        function editar_menu($data)
        {
            $this->multilang = 0;
            /*Revisar si estan unstalados los campos para otros lenguajes*/
            $strSqlValidarColumna = "
                SHOW COLUMNS FROM 
                    core_cat_menu
                WHERE 
                    Field='titulo_en'";
            
            if (sizeof(query2array($strSqlValidarColumna)) > 0) {
                $this->multilang = 1;
            }

            $en_ventana = '';
            $onclick = '';
            $href = '';
            $titulo = '';
            extract($data);
            $strSql = "
                SELECT 
                    uuid
                FROM
                    core_cat_menu  
                WHERE
                    id_menu = '$id_menu'";
            $menu = query2vars($strSql);
            $uuid_anterior = $menu['uuid'];

            $campos_lang = '';
            if($this->multilang == 1){
                $campos_lang .= ",
                    titulo_en = '".isset_or($titulo_en, '')."'
                    ";
            }

            $strSQLEditarMenu = "
                UPDATE core_cat_menu
                SET	titulo='$titulo',
                    uuid='$uuid',
                    onclick='$onclick',
                    en_ventana='$en_ventana',
                    href='$href'
                    $campos_lang
                WHERE id_menu='$id_menu'
                ";

            query($strSQLEditarMenu);

            
            if ($uuid != $uuid_anterior) {
                $strSqlEliminar = "
                    UPDATE
                    core_cat_menu
                    set
                    parent_uuid = '$uuid'
                    WHERE
                    parent_uuid ='$uuid_anterior'";
                query($strSqlEliminar);

            }
        }
        
        function menu_subir_orden($uuid, $parent_uuid)
        {
            $strActualizar = "
                UPDATE
                core_cat_menu
                SET
                orden=orden-1
                WHERE
                parent_uuid='$parent_uuid'
                AND
                uuid='$uuid'
                AND 
                orden>=2";
            query($strActualizar);

            $menu = $this->menu_info($uuid);
            $strActualizar = "
                UPDATE
                core_cat_menu
                SET
                orden=orden+1
                WHERE
                parent_uuid='$parent_uuid'
                AND
                uuid<>'$uuid'
                AND orden='{$menu['orden']}'";
            query($strActualizar);

            
            return true;
        }
        
        function menu_bajar_orden($uuid, $parent_uuid)
        {
            $strActualizar = "
                UPDATE
                core_cat_menu
                SET
                orden=orden+1
                WHERE
                parent_uuid='$parent_uuid'
                AND
                uuid='$uuid'
                AND 
                ((orden+1)<next_menu_order('$parent_uuid'))";
            query($strActualizar);

            $menu = $this->menu_info($uuid);
            $strActualizar = "
                UPDATE
                core_cat_menu
                SET
                orden=orden-1
                WHERE
                parent_uuid='$parent_uuid'
                AND
                uuid<>'$uuid'
                AND orden='{$menu['orden']}'";
            query($strActualizar);

            
            return true;
        }
        
        function menu_eliminar_recursivo($uuid)
        {
            $strSqlEliminar = "
                DELETE 
                FROM
                core_cat_menu
                WHERE
                uuid='$uuid'";
            query($strSqlEliminar);

            $strSQlLista = "
                SELECT 
                * 
                FROM 
                core_cat_menu 
                WHERE 
                parent_uuid='$uuid'";
            $res = query($strSQlLista);

            while ($obj = mysql_fetch_object($res)) {
                $this->menu_eliminar_recursivo($obj->uuid);
            }
        }
        
        function obtener_menu_recursivo($uuid)
        {
            $strSqlLista = "
                SELECT 
                * 
                FROM 
                core_cat_menu 
                WHERE 
                uuid='$uuid'";
            $this->menu_array_single[] = query2vars($strSqlLista);
            $strSqlItems = "
                SELECT COUNT(*) AS total
                FROM core_cat_menu
                WHERE
                parent_uuid = '$uuid';
            ";
            $items = query2vars($strSqlItems);
            
            if ($items['total'] > 0) {
                $strSqlLista = "
                    SELECT 
                    * 
                    FROM 
                    core_cat_menu 
                    WHERE 
                    parent_uuid='$uuid'
                    ORDER BY
                    orden";
                $res = query($strSqlLista);

                while ($obj = mysql_fetch_object($res)) {
                    $this->obtener_menu_recursivo($obj->uuid);
                }
            }
            
            return $this->menu_array_single;
        }
    }
}
?>
