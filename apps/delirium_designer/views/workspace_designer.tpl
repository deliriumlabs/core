<script type="text/javascript">

    $j = jQuery.noConflict();
    set_debug(true);
    try{ 

    //Propiedades del documento
    var rpt_props = [];

    rpt_props['uuid'] = '{uuid}';
    rpt_props['uuid_modulo'] = '{uuid_modulo}';
    rpt_props['chr_titulo'] = '{chr_titulo}';
    rpt_props['top_margin'] = {top_margin};
    rpt_props['right_margin'] = {right_margin};
    rpt_props['bottom_margin'] = {bottom_margin};
    rpt_props['left_margin'] = {left_margin};
    rpt_props['orientation'] = '{orientation}';
    rpt_props['width'] = {width};
    rpt_props['height'] = {height};

    //Parametros guardados
    rpt_config = [];
    rpt_config['group_count'] = 0;
    {{START lista_params_config}}
    rpt_config['[param]'] = "[value]";
    {{END lista_params_config}}


    //Secciones del documento
    var rpt_secs = [];
    rpt_secs['header'] = [];
    rpt_secs['header']['height'] = {header_height};

    rpt_secs['content'] = [];
    rpt_secs['content']['height'] = {content_height};
    rpt_secs['content']['sql_alias'] = '{content_alias}';

    rpt_secs['footer'] = [];
    rpt_secs['footer']['height'] = {footer_height};

    //Número de objetos creados
    var obj_count = 1;
    //Objetos del documento
    var rpt_objs = [];
    rpt_objs['header'] = [];
    rpt_objs['content'] = [];
    rpt_objs['footer'] = [];

    //Variable para la cantidad de grupos
    var group_count = 0;

    //Variables
    rpt_vars = [];
    rpt_vars_tipos = {
        'string':'%VAR_STRING%',
        'number':'%VAR_NUMBER%',
        'date':'%VAR_DATE%',
        'bool':'%VAR_BOOL%'
    };

    //Consultas del documento
    var rpt_sql = [];
    {{START lista_consultas}}
    rpt_sql['[alias]'] = [];
    rpt_sql['[alias]']['sql'] = "[sql]";
    {{END lista_consultas}}

    {{START lista_objetos_header}}
    rpt_objs['header']['[id]'] = [];
    rpt_objs['header']['[id]']['id'] = '[id]';
    rpt_objs['header']['[id]']['tipo'] = '[tipo]';
    rpt_objs['header']['[id]']['seccion'] = 'header';
    rpt_objs['header']['[id]']['font-family'] = '[font-family]';
    rpt_objs['header']['[id]']['font-size'] = '[font-size]';
    rpt_objs['header']['[id]']['font-bold'] = '[font-bold]';
    rpt_objs['header']['[id]']['font-italic'] = '[font-italic]';
    rpt_objs['header']['[id]']['font-underline'] = '[font-underline]';
    rpt_objs['header']['[id]']['font-color-R']=[font-color-R];
    rpt_objs['header']['[id]']['font-color-G']=[font-color-G];
    rpt_objs['header']['[id]']['font-color-B']=[font-color-B];
    rpt_objs['header']['[id]']['font-bgcolor-R']=[font-bgcolor-R];
    rpt_objs['header']['[id]']['font-bgcolor-G']=[font-bgcolor-G];
    rpt_objs['header']['[id]']['font-bgcolor-B']=[font-bgcolor-B];
    rpt_objs['header']['[id]']['x'] = '[x]';
    rpt_objs['header']['[id]']['y'] = '[y]';
    rpt_objs['header']['[id]']['width'] = '[width]';
    rpt_objs['header']['[id]']['height'] = '[height]';
    rpt_objs['header']['[id]']['txt'] = ('[txt]').replace("#|", "{").replace("|#", "}");
    if('[tipo]' == 'field' || '[tipo]' == 'barcode' ){
        rpt_objs['header']['[id]']['txt'] = '[field]';
        rpt_objs['header']['[id]']['field'] = '[field]';
    }

    rpt_objs['header']['[id]']['align'] = '[align]';

    {{END lista_objetos_header}}

    {{START lista_objetos_content}}
    rpt_objs['content']['[id]'] = [];
    rpt_objs['content']['[id]']['id'] = '[id]';
    rpt_objs['content']['[id]']['tipo'] = '[tipo]';
    rpt_objs['content']['[id]']['seccion'] = 'content';
    rpt_objs['content']['[id]']['font-family'] = '[font-family]';
    rpt_objs['content']['[id]']['font-size'] = '[font-size]';
    rpt_objs['content']['[id]']['font-bold'] = '[font-bold]';
    rpt_objs['content']['[id]']['font-italic'] = '[font-italic]';
    rpt_objs['content']['[id]']['font-underline'] = '[font-underline]';
    rpt_objs['content']['[id]']['font-color-R']=[font-color-R];
    rpt_objs['content']['[id]']['font-color-G']=[font-color-G];
    rpt_objs['content']['[id]']['font-color-B']=[font-color-B];
    rpt_objs['content']['[id]']['font-bgcolor-R']=[font-bgcolor-R];
    rpt_objs['content']['[id]']['font-bgcolor-G']=[font-bgcolor-G];
    rpt_objs['content']['[id]']['font-bgcolor-B']=[font-bgcolor-B];
    rpt_objs['content']['[id]']['x'] = '[x]';
    rpt_objs['content']['[id]']['y'] = '[y]';
    rpt_objs['content']['[id]']['width'] = '[width]';
    rpt_objs['content']['[id]']['height'] = '[height]';
    rpt_objs['content']['[id]']['txt'] = ('[txt]').replace("#|", "{").replace("|#", "}");
    if('[tipo]' == 'field'){
        rpt_objs['content']['[id]']['txt'] = '[field]';
        rpt_objs['content']['[id]']['field'] = '[field]';
    }
    rpt_objs['content']['[id]']['align'] = '[align]';
    {{END lista_objetos_content}}

    {{START lista_objetos_footer}}
    rpt_objs['footer']['[id]'] = [];
    rpt_objs['footer']['[id]']['id'] = '[id]';
    rpt_objs['footer']['[id]']['tipo'] = '[tipo]';
    rpt_objs['footer']['[id]']['seccion'] = 'footer';
    rpt_objs['footer']['[id]']['font-family'] = '[font-family]';
    rpt_objs['footer']['[id]']['font-size'] = '[font-size]';
    rpt_objs['footer']['[id]']['font-bold'] = '[font-bold]';
    rpt_objs['footer']['[id]']['font-italic'] = '[font-italic]';
    rpt_objs['footer']['[id]']['font-underline'] = '[font-underline]';
    rpt_objs['footer']['[id]']['font-color-R']=[font-color-R];
    rpt_objs['footer']['[id]']['font-color-G']=[font-color-G];
    rpt_objs['footer']['[id]']['font-color-B']=[font-color-B];
    rpt_objs['footer']['[id]']['font-bgcolor-R']=[font-bgcolor-R];
    rpt_objs['footer']['[id]']['font-bgcolor-G']=[font-bgcolor-G];
    rpt_objs['footer']['[id]']['font-bgcolor-B']=[font-bgcolor-B];
    rpt_objs['footer']['[id]']['x'] = '[x]';
    rpt_objs['footer']['[id]']['y'] = '[y]';
    rpt_objs['footer']['[id]']['width'] = '[width]';
    rpt_objs['footer']['[id]']['height'] = '[height]';
    rpt_objs['footer']['[id]']['txt'] = ('[txt]').replace("#|", "{").replace("|#", "}");
    if('[tipo]' == 'field'){
        rpt_objs['footer']['[id]']['txt'] = '[field]';
        rpt_objs['footer']['[id]']['field'] = '[field]';
    }
    rpt_objs['footer']['[id]']['align'] = '[align]';
    {{END lista_objetos_footer}}


    //Lista de fonts
    font_list = {
        'Courier':'Courier',
        'Helvetica':'Helvetica',
        'Arial':'Arial',
        'Times':'Times',
        'Symbol':'Symbol'
    };

    align_list = {
        'L':'%ALINEAR_IZQUIERDA%',
        'C':'%ALINEAR_CENTRADO%',
        'R':'%ALINEAR_DERECHA%'
    };

    //Ajustes en caso de otros objetos
    var offset_ajuste_top = jQuery('#menu').outerHeight();
    offset_ajuste_top += jQuery('#rpt-menu').outerHeight();
    var offset_ajuste_left = jQuery('#dd-panel-objects').outerWidth();

    //Coeficientes de conversion pixeles a milimetros
    var px_mm_x = 0;
    var px_mm_y = 0;

    //$j(debug('step 1'));
    window.onload = function(){
        loadcssfile("{PATH_TPL}/css/style.css");

        jQuery(function(){
            $j = jQuery.noConflict();

            $j('#dd-panel-objects').tabs().width(300);
            $j('#dd-rpt-area').css('left', $j('#dd-panel-objects').outerWidth());
            offset_ajuste_left = jQuery('#dd-panel-objects').outerWidth();

            $j('#dd-rpt-area, #delirium-designer').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()));
            $j('#dd-panel-objects').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()+10));
            $j('#dd-rpt-area').width($j(window).width()-($j('#dd-panel-objects').outerWidth()+15));
            $j('#delirium-designer').width($j(window).width());
            $j(window).bind("resize",function(){
                $j(' #dd-rpt-area, #delirium-designer').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()));
                $j('#dd-panel-objects').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()+10));
                $j('#dd-rpt-area').width($j(window).width()-($j('#dd-panel-objects').outerWidth()));
            });

            //Obtener coeficiente de conversion px  a mm
            $j('#delirium-designer').append('<div id="_ctrl_mm" style="left:-100%;right:-100%;position:absolute;width:1in;height:1in;padding:0"></div>');
            var dpi = $j('#_ctrl_mm').attr('offsetWidth');
            px_mm_x = px_mm_y = 25.4 * 1 / dpi;

            //Inicializar uuid del documento
            $j('#uuid').val(rpt_props['uuid']).bind("keyup",function(){
                rpt_props['uuid']  = $j(this).val();
            });

            //Inicializar uuid_modulo del documento
            $j('#uuid_modulo').val(rpt_props['uuid_modulo']).bind("keyup",function(){
                rpt_props['uuid_modulo']  = $j(this).val();
            });

            //Inicializar titulo del documento
            $j('#chr_titulo').val(rpt_props['chr_titulo']).bind("keyup",function(){
                rpt_props['chr_titulo']  = $j(this).val();
            });

            //Inicializar altura del documento
            $j('#height').val(rpt_props['height']).bind("keyup",function(){
                rpt_props['height']  = $j(this).val();
                $j('#dd-panel-rpt').height($j(this).val() / px_mm_y);
            });
            $j('#dd-panel-rpt').height(rpt_props['height'] / px_mm_y);

            //Inicializar ancho del documento
            $j('#width').val(rpt_props['width']).bind("keyup",function(){
                rpt_props['width']  = $j(this).val();
                $j('#dd-panel-rpt').width($j(this).val() / px_mm_x);
            });
            $j('#dd-panel-rpt').width(rpt_props['width'] / px_mm_x);

            //Habilitar el resize de las secciones del documento
            $j('.rpt-container').resizable({
                stop : function(event, ui){
                    if($j(this).hasClass("rpt-header")){
                        seccion = "header"; 
                    }
                    if($j(this).hasClass("rpt-content")){
                        seccion = "content"; 
                    }
                    if($j(this).hasClass("rpt-footer")){
                        seccion = "footer"; 
                    }
                    var id = $j(this).attr('id');
                    jQuery(view_seccion_properties(seccion, id));

                    //Ajustar cooredenadas de los objetos despues de hacer el resize de alguna sección
                    for(seccion in rpt_objs){
                        for(indice in rpt_objs[seccion]) {
                            jQuery(view_properties(seccion, rpt_objs[seccion][indice]['id'], rpt_objs[seccion][indice]['tipo']));
                        }
                    }
                },
                handles : 's'
            });

            //Habilitar cada herramienta como dragable
            $j("#label, #textfield, #picture, #fx, #barcode").draggable(
                { 
                    revert: 'invalid',
                    helper: 'clone' ,
                    scroll:false,
                    appendTo:'#delirium-designer',
                    zIndex:300
                });

            //Marcar las secciones del documento para recibir objetos dragables
            $j("#header-1, #content-1, #footer-1").droppable(
                {
                    drop : function(event, ui){
                        seccion = "";
                        if($j(this).hasClass("rpt-header")){
                           seccion = "header"; 
                        }
                        if($j(this).hasClass("rpt-content")){
                           seccion = "content"; 
                        }
                        if($j(this).hasClass("rpt-footer")){
                           seccion = "footer"; 
                        }
                        jQuery(crear_objeto(this, ui, seccion));
                    } 
                }
            ).height(100);
            
            $j("#header-1").height(rpt_secs['header']['height'] / px_mm_y);
            $j("#content-1").height(rpt_secs['content']['height'] / px_mm_y);
            $j("#footer-1").height(rpt_secs['footer']['height'] / px_mm_y);
            //Relacionar botones auxliares
            $j("#btn_consultas").bind("click",function(){
                view_consultas_admin();
            });
            $j("#btn_preview").bind("click",function(){
                preview();
            });
            $j("#btn_save").bind("click",function(){
                save();
            });
            $j("#btn_exportar").bind("click",function(){
                exportar();
            });


            //$j(debug('step 2'));
            //Crear las secciones guardadas
            for( x = 1; x <= rpt_config['group_count']; x++){ //fix para synt>
                agregar_seccion_agrupamiento(); 
            }

            //$j(debug('step 3'));
            try{
            {{START lista_propiedades_groups}}
                rpt_secs['[group]']['group_by'] = '[group_by]';
                rpt_secs['[group]']['sql_alias'] = '[sql_alias]';
                rpt_secs['[group]']['height'] = '[height]';
                rpt_secs['[group]']['print_from'] = '[print_from]';
                if( isNaN('[custom_top_start]') ){
                    rpt_secs['[group]']['custom_top_start'] = 0;
                }else{
                    rpt_secs['[group]']['custom_top_start'] = '[custom_top_start]';
                }
                $j('#[group]').height([height] / px_mm_y);
            {{END lista_propiedades_groups}}
            }catch(m){alert(m);}

            //$j(debug('step 4'));
            {{START lista_objetos_groups}}
            rpt_objs['[group]']['[id]'] = [];
            rpt_objs['[group]']['[id]']['id'] = '[id]';
            rpt_objs['[group]']['[id]']['tipo'] = '[tipo]';
            rpt_objs['[group]']['[id]']['seccion'] = '[group]';
            rpt_objs['[group]']['[id]']['font-family'] = '[font-family]';
            rpt_objs['[group]']['[id]']['font-size'] = '[font-size]';
            rpt_objs['[group]']['[id]']['font-bold'] = '[font-bold]';
            rpt_objs['[group]']['[id]']['font-italic'] = '[font-italic]';
            rpt_objs['[group]']['[id]']['font-underline'] = '[font-underline]';
            rpt_objs['[group]']['[id]']['font-color-R']=[font-color-R];
            rpt_objs['[group]']['[id]']['font-color-G']=[font-color-G];
            rpt_objs['[group]']['[id]']['font-color-B']=[font-color-B];
            rpt_objs['[group]']['[id]']['font-bgcolor-R']=[font-bgcolor-R];
            rpt_objs['[group]']['[id]']['font-bgcolor-G']=[font-bgcolor-G];
            rpt_objs['[group]']['[id]']['font-bgcolor-B']=[font-bgcolor-B];
            rpt_objs['[group]']['[id]']['x'] = '[x]';
            rpt_objs['[group]']['[id]']['y'] = '[y]';
            rpt_objs['[group]']['[id]']['width'] = '[width]';
            rpt_objs['[group]']['[id]']['height'] = '[height]';
            rpt_objs['[group]']['[id]']['txt'] = ('[txt]').replace("#|", "{").replace("|#", "}");
            if('[tipo]' == 'field'){
                rpt_objs['[group]']['[id]']['txt'] = '[field]';
                rpt_objs['[group]']['[id]']['field'] = '[field]';
            }
            
            rpt_objs['[group]']['[id]']['align'] = '[align]';

            {{END lista_objetos_groups}}

            //$j(debug('step 5'));
            /*
                CARGAR LOS OBJETOS DE CADA SECCION EN CASO DE EDICION
            */
            for(seccion in  rpt_objs){

                for(obj in rpt_objs[seccion]){
                    cargar_objeto(seccion, rpt_objs[seccion][obj]['id']);
                }

            }
            
            /*
                CARGAR LOS CAMPOS DE CADA ALIAS
            */
            //$j(debug('step 6'));
            cargar_fields();

            //$j(debug('step 7'));
            //hideWaitWindow();
            $j('#dd-rpt-area, #delirium-designer').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()));
            $j('#dd-panel-objects').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()+10));
            $j('#dd-rpt-area').width($j(window).width()-($j('#dd-panel-objects').outerWidth()));
            $j('#delirium-designer').width($j(window).width());
            $j(window).bind("resize",function(){
                $j(' #dd-rpt-area, #delirium-designer').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()));
                $j('#dd-panel-objects').height($j(window).height()-($j('#footer').outerHeight()+$j('#menu').outerHeight()+$j('#rpt-menu').outerHeight()+10));
                $j('#dd-rpt-area').width($j(window).width()-($j('#dd-panel-objects').outerWidth()));
            });


            $j('#frm-rpt-vars').dialog({
                        bgiframe: true,
                        autoOpen: false,
                        height: 600,
                        width: 600,
                        modal: true
                    });

            //Ajustar la altura de documento tomando en cuenta los enc de cada seccion
            document_height();
        });

    }

    function document_height(){
        var height_doc = $j('#dd-panel-rpt').height();
        height_doc += $j('#header-1-enc').outerHeight();
        height_doc += $j('#details-1-enc').outerHeight();
        height_doc += $j('#footer-1-enc').outerHeight();
        
        for( x = 1; x <= rpt_config['group_count']; x++){ //fix para synt>
            height_doc += ( $j('#header-group'+x+'-enc').outerHeight()+$j('#footer-group'+x+'-enc').outerHeight() ) ;
        }

        $j('#dd-panel-rpt').height(height_doc);
    }

    //Funciones utilizadas

    //Crear un objeto y lo agrega al documento
    function crear_objeto(origen, ui, seccion){
        if($j(ui.helper).hasClass("ui-widget-content-dd-tool") && !$j(ui.helper).hasClass("ui-dialog")){
            var tipo = '';
            //Insertar nuevo label
            if( $j("#"+ui.helper.attr("id")+" a").hasClass("label") && tipo == '' ){
                $j(origen).append('<div id="label'+obj_count+'" class="rpt-item" ><span style="font-family:Arial;font-size:15pt" >New Label</span></div>');
                tipo = 'label';
            }

            //Insertar nuevo campo
            if( $j("#"+ui.helper.attr("id")+" a").hasClass("textfield") && tipo == '' ){
                $j(origen).append('<div id="field'+obj_count+'" class="rpt-item"><span style="font-family:Arial;font-size:15pt" >New field</span></div>');
                tipo = 'field';
            }

            //Insertar imagen
            if( $j("#"+ui.helper.attr("id")+" a").hasClass("picture") && tipo == '' ){
                $j(origen).append('<div id="picture'+obj_count+'" class="rpt-item">New picture</div>');
                tipo = 'picture';
            }

            //Insertar nuevo fx
            if( $j("#"+ui.helper.attr("id")+" a").hasClass("fx") && tipo == '' ){
                $j(origen).append('<div id="fx'+obj_count+'" class="rpt-item" ><span style="font-family:Arial;font-size:15pt" >1+1</span></div>');
                tipo = 'fx';
            }

            //Insertar nuevo barcode
            if( $j("#"+ui.helper.attr("id")+" a").hasClass("barcode") && tipo == '' ){
                $j(origen).append('<div id="barcode'+obj_count+'" class="rpt-item" ><span class="barcode" style="font-family:Arial;font-size:15pt;background-position:center top;padding: 20px 1px 4px 0px !important;text-align:center;" >012345678</span></div>');
                tipo = 'barcode';
            }


            //$j(debug(tipo+obj_count));

            $j("#"+tipo+obj_count).offset({top : (ui.offset.top ) , left : ui.offset.left});

            var id =tipo+obj_count;
            $j("#"+tipo+obj_count).draggable({// Habilitar el objeto como dragable
                containment: origen, 
                scroll: false,
                drag: function(){
                    update_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                },
                start: function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                },
                stop: function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                }
            }).resizable({//Habilitar el risize del objeto
                alsoResize : "#"+$j(this).attr("id")+' span',
                resize : function(){
                    update_properties(seccion, id, rpt_objs[seccion][id]['tipo']);
                    $j("#"+$j(this).attr("id")+' span').width($j("#"+$j(this).attr("id")).width()-1);
                    $j("#"+$j(this).attr("id")+' span').height($j("#"+$j(this).attr("id")).height()-1);
                },
                start: function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                }
            }).bind("click",function(){//Asignar la funcion para mostrar las propiedades del objeto
                view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
            });

            rpt_objs[seccion][tipo+obj_count] = [];
            rpt_objs[seccion][tipo+obj_count]['id'] = tipo+obj_count;
            rpt_objs[seccion][tipo+obj_count]['tipo'] = tipo;
            rpt_objs[seccion][tipo+obj_count]['seccion'] = seccion;
            rpt_objs[seccion][tipo+obj_count]['font-family'] = 'Arial';
            rpt_objs[seccion][tipo+obj_count]['font-size'] = '15';
            rpt_objs[seccion][tipo+obj_count]['font-bold'] = '';
            rpt_objs[seccion][tipo+obj_count]['font-italic'] = '';
            rpt_objs[seccion][tipo+obj_count]['font-underline'] = '';
            rpt_objs[seccion][tipo+obj_count]['font-color-R']=0;
            rpt_objs[seccion][tipo+obj_count]['font-color-G']=0;
            rpt_objs[seccion][tipo+obj_count]['font-color-B']=0;
            rpt_objs[seccion][tipo+obj_count]['font-bgcolor-R']=255;
            rpt_objs[seccion][tipo+obj_count]['font-bgcolor-G']=255;
            rpt_objs[seccion][tipo+obj_count]['font-bgcolor-B']=255;

            rpt_objs[seccion][tipo+obj_count]['align'] = 'L';

            var r = rpt_objs[seccion][tipo+obj_count]['font-color-R'];
            var g = rpt_objs[seccion][tipo+obj_count]['font-color-G'];
            var b = rpt_objs[seccion][tipo+obj_count]['font-color-B'];
            $j('#'+tipo+obj_count+' span').css({'color':'rgb('+r+','+g+','+b+')'});

            view_properties(seccion, tipo+obj_count, tipo);

            obj_count++;
        }
    }

    //Cargar un objeto guardado
    function cargar_objeto(seccion, id){
        hideWaitWindow();
        showWaitWindow('%LBL_ESPERE%');
            //$j(debug('Cargando '+id));

            var max = id.replace(/(label|field|picture|fx|barcode)/g,'');

            if(max >= obj_count){
                obj_count = Number(max) + 1;
            }

            objeto = rpt_objs[seccion][id];
            var tipo = objeto['tipo'];

            seccion_id = seccion;
            if( seccion == 'header' || seccion == 'content' || seccion == 'footer'){
                seccion_id = seccion+'-1';
            }
            //Insertar nuevo label
            if(tipo == 'label'){
                $j('#'+seccion_id).append('<div id="'+id+'" class="rpt-item" ><span style="font-family:'+objeto['font-family']+';font-size:'+objeto['font-size']+'pt" >'+objeto['txt']+'</span></div>');
                tipo = 'label';
            }

            //Insertar nuevo campo
            if(tipo == 'field'){
                $j('#'+seccion_id).append('<div id="'+id+'" class="rpt-item"><span style="font-family:'+objeto['font-family']+';font-size:'+objeto['font-size']+'pt" >'+objeto['txt']+'</span></div>');
                tipo = 'field';
            }

            //Insertar imagen
            if(tipo == 'picture'){
                $j('#'+seccion_id).append('<div id="'+id+'" class="rpt-item">New picture</div>');
                tipo = 'picture';
            }

            //Insertar nuevo fx
            if(tipo == 'fx'){
                $j('#'+seccion_id).append('<div id="'+id+'" class="rpt-item" ><span style="font-family:'+objeto['font-family']+';font-size:'+objeto['font-size']+'pt" >'+objeto['txt']+'</span></div>');
                tipo = 'fx';
            }

            //Insertar nuevo barcode
            if(tipo == 'barcode'){
                $j('#'+seccion_id).append('<div id="'+id+'" class="rpt-item" ><span class="barcode" style="font-family:'+objeto['font-family']+';font-size:'+objeto['font-size']+'pt;background-position:center top;padding: 20px 1px 4px 0px !important;text-align:center;" >'+objeto['txt']+'</span></div>');
                tipo = 'barcode';
            }

            var ajuste_y = fix_y = 0;
            
            switch(seccion){
                case "header":
                    ajuste_y = 0;
                    fix_y = (.10 / px_mm_y);
                    break;
                case "content":

                    fix_y = .10;

                    if( group_count > 0 ){
                        for ( x = 1; x <= group_count; x++ ){
                            fix_y+=.10;
                        }
                    } 

                    fix_y = (fix_y / px_mm_y) *-1;
                    break;

                case "xfooter":
                    ajuste_y = ($j("#header-1").height() + $j("#header-1-enc").height() +  $j("#content-1-enc").height() + $j("#content-1").height() +  $j("#footer-1-enc").height() ) + 8.4;
                    break;

                default:
                    var re = /^header-group(.*)/;
                    var idx = seccion.replace('header-group-','');

                    if( re.test(seccion) ){
                        fix_y+=.40;
                        if( group_count > 0 && idx > 1 ){
                            fix_y = 0;
                            for ( x = 1 ; x < idx ; x++ ){
                                fix_y+=.05;
                            }
                        } 
                        fix_y = (fix_y / px_mm_y) *-1;
                    }

                    break;
            }

            var x = (objeto['x'] / px_mm_x);
            var y = objeto['y'] / px_mm_y;
            //y -= ajuste_y;
            y += fix_y;

            var w = (objeto['width'] / px_mm_x);
            var h = (objeto['height'] / px_mm_x);

            //$j(debug('y : '+y));
            //$j(debug('-----'));

            $j("#"+id).css({top: y, left:x, position : 'absolute'}).width(w).height(h);
            $j("#"+id+' span') .width(w - 1).height(h - 1);
            $j("#"+id).draggable({
                containment: '#'+seccion_id, 
                scroll: false,
                drag: function(){
                    update_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                },
                start: function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                },
                stop: function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                }
            }).resizable({
                alsoResize : "#"+$j(this).attr("id")+' span',
                resize : function(){
                    update_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                    $j("#"+$j(this).attr("id")+' span').width($j("#"+$j(this).attr("id")).width()-1);
                    $j("#"+$j(this).attr("id")+' span').height($j("#"+$j(this).attr("id")).height()-1);
                },
                start: function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
                }
            }).bind("click",function(){
                    view_properties(seccion, $j(this).attr("id"), rpt_objs[seccion][$j(this).attr("id")]['tipo']);
            });


            var r = rpt_objs[seccion][id]['font-color-R'];
            var g = rpt_objs[seccion][id]['font-color-G'];
            var b = rpt_objs[seccion][id]['font-color-B'];
            $j('#'+id+' span').css({'color':'rgb('+r+','+g+','+b+')'});

            switch(rpt_objs[seccion][id]['align']){
                case "L":
                    $j('#'+id+' span').css({'text-align':'left'});
                    break;
                case "C":
                    $j('#'+id+' span').css({'text-align':'center'});
                    break;
                case "R":
                    $j('#'+id+' span').css({'text-align':'right'});
                    break;
            }

            if( rpt_objs[seccion][id]['font-bold'] == 'B' ){
                $j('#'+id+' span').css({'font-weight':'bold'});
            }
            
            if( rpt_objs[seccion][id]['font-italic'] == 'I' ){
                $j('#'+id+' span').css({'font-style':'italic'});
            }

            if( rpt_objs[seccion][id]['font-underline'] == 'U' ){
                $j('#'+id+' span').css({'text-decoration':'underline'});
            }
        hideWaitWindow();

    }

    //Obtiene la lista de aligns disponibles
    function get_aligns(seccion, id){
        var options = '<select type="text" id="'+id+'_align" >';
        options += '<option value="">%SELECCIONE%</option>';
        align_selected = rpt_objs[seccion][id]['align'];
        for(align in align_list){
            var selected = '';
            //align_desc = align_list[align];
            if(align == align_selected){
                selected = 'selected';
            }
            options += '<option value="'+align+'" '+selected+' >'+align_list[align]+'</option>';
        } 
        return options;
    }

    //Obtiene la lista de fonts disponibles
    function get_fonts(seccion, id){
        var options = '<select type="text" id="'+id+'_font_family" >';
        options += '<option value="">%SELECCIONE%</option>';
        font_selected = rpt_objs[seccion][id]['font-family'];
        for(font in font_list){
            var selected = '';
            font_family = font_list[font];
            if(font_family == font_selected){
                selected = 'selected';
            }
            options += '<option value="'+font_family+'" '+selected+' >'+font_family+'</option>';
        } 
        return options;
    }

    //Obtiene la lista de campos obtenidos de las consultas
    function get_fields(seccion,id){
        var options = '';
        options += '<option value="%SELECCIONE%">%SELECCIONE%</option>';
        var campo_seleccionado = rpt_objs[seccion][id]['field'];
        for(consulta in rpt_sql){
            alias = rpt_sql[consulta];
            for(var x = 0; x < alias['fields'].length; x++){//ajuste para color>
                var selected = '';
                if(consulta+'.'+alias['fields'][x] == campo_seleccionado){
                    selected = 'selected'; 
                }
                options += '<option value="'+consulta+'.'+alias['fields'][x]+'" '+selected+' >'+consulta+'.'+alias['fields'][x]+'</option>';
            }
        } 
        return options;
    }

    //Obtiene la lista de campos para agrupar
    function get_fields_group_by(seccion){
        var options = '<select id="'+seccion+'_group_by" >';
        options += '<option value="%SELECCIONE%">%SELECCIONE%</option>';
        var campo_seleccionado = rpt_secs[seccion]['group_by'];
        for(consulta in rpt_sql){
            alias = rpt_sql[consulta];
            for(var x = 0; x < alias['fields'].length; x++){//ajuste para color>
                var selected = '';
                if(consulta+'.'+alias['fields'][x] == campo_seleccionado){
                    selected = 'selected'; 
                }
                options += '<option value="'+consulta+'.'+alias['fields'][x]+'" '+selected+' >'+consulta+'.'+alias['fields'][x]+'</option>';
            }
        } 
        option += '</select>';
        return options;
    }

    //Obtiene la lista de los origenes disponibles para imprimir los footers
    function get_print_from_details(seccion){
        var options = '<select id="'+seccion+'_print_from" >';
        //options += '<option value="%SELECCIONE%">%SELECCIONE%</option>';
        var campo_seleccionado = rpt_secs[seccion]['print_from'];

        if( campo_seleccionado == 'end' ){
            options += '<option value="custom_top_start" >%CUSTOM_TOP_START%</option>';
            options += '<option value="end" selected >%END_DETAILS%</option>';
        }else{
            options += '<option value="custom_top_start" selected >%CUSTOM_TOP_START%</option>';
            options += '<option value="end" >%END_DETAILS%</option>';
        }

        option += '</select>';
        return options;
    }

    //Cargar los campos de un reporte guardado
    function cargar_fields(){
        for(alias in rpt_sql){
            rpt_sql[alias]['sql'] = (rpt_sql[alias]['sql']).replace(/%nr%/img, '\r')
            mvcPost('DeliriumDesigner::get_fields', 'sql='+encode(rpt_sql[alias]['sql']), '',function(response){
                try{
                    rpt_sql[alias]['fields'] = response.split(',');

                    try{
                        hideWaitWindow();
                        showWaitWindow("%LBL_ESPERE%","%LBL_ESPERE%");
                        for(seccion in rpt_objs){
                            for(indice in rpt_objs[seccion]) {
                                view_properties(seccion, rpt_objs[seccion][indice]['id'], rpt_objs[seccion][indice]['tipo']);
                            }
                        }
                        hideWaitWindow();
                    }catch(ex){alert(ex);}
                }catch(e){
                    alert(e);
                }
            });
        }
    }

    function get_ajuste_y(seccion){

        var ajuste_y = 0;
        switch(seccion){
            case "header":
                //ajuste_y = ($j("#header-1-enc").height()) + 2.4 ;
                ajuste_y = ($j("#header-1-enc").outerHeight()) ;
            break;

            case "content":
                //ajuste_y = ($j("#header-1").outerHeight() + $j("#header-1-enc").outerHeight() +  $j("#content-1-enc").outerHeight() ) + (3 + 2.4) ;
                ajuste_y = ($j("#header-1").outerHeight() + $j("#header-1-enc").outerHeight() +  $j("#content-1-enc").outerHeight() );
                if( group_count > 0 ){
                    for ( x = 1; x <= group_count; x++ ){
                        //ajuste_y += $j("#header-group-"+x).height() + $j("#header-group-"+x+"-enc").height();
                        ajuste_y += $j("#header-group-"+x).outerHeight() + $j("#header-group-"+x+"-enc").outerHeight();
                    }
                    //ajuste_y += (group_count + 2.4);
                } 
                break;

            case "footer":
                ajuste_y = $j("#header-1").outerHeight() + $j("#header-1-enc").outerHeight() 
                ajuste_y +=  $j("#content-1").outerHeight() + $j("#content-1-enc").outerHeight();
                ajuste_y +=  $j("#footer-1-enc").outerHeight();
                //ajuste_y += (3 + 2.4) ;
                if( group_count > 0 ){
                    for ( x = 1; x <= group_count; x++ ){
                        ajuste_y += $j("#header-group-"+x).outerHeight() + $j("#header-group-"+x+"-enc").outerHeight();
                        ajuste_y += $j("#footer-group-"+x).outerHeight() + $j("#footer-group-"+x+"-enc").outerHeight();
                    }
                    //ajuste_y += (group_count + 2.4);
                } 
                break;

            default:
                var re_header = /^header-group(.*)/;

                if( re_header.test(seccion) ){
                var idx = seccion.replace('header-group-','');
                    //ajuste_y = ($j("#header-1").height() + $j("#header-1-enc").height() + $j("#header-group-"+idx+"-enc").height() ) + (1 + 2.4) ;
                    ajuste_y = ($j("#header-1").outerHeight() + $j("#header-1-enc").outerHeight() + $j("#header-group-"+idx+"-enc").outerHeight() ) ;
                    if( group_count > 0 && idx > 1 ){
                        for ( x = 1 ; x < idx ; x++ ){
                            ajuste_y += $j("#header-group-"+x).outerHeight() + $j("#header-group-"+x+"-enc").outerHeight();
                        }
                        //ajuste_y += (group_count + 2.4);
                    } 
                }

                var re_footer = /^footer-group(.*)/;
                if( re_footer.test(seccion) ){
                    var idx = seccion.replace('footer-group-','');
                    //$j(debug(seccion));
                
                    //ajuste_y = ($j("#header-1").height() + $j("#header-1-enc").height() + $j("#footer-group-"+idx+"-enc").height() ) + (1 + 2.4) ;
                    ajuste_y = ($j("#header-1").outerHeight() + $j("#header-1-enc").outerHeight() + $j("#footer-group-"+idx+"-enc").outerHeight() );
                    ajuste_y +=  $j("#content-1").outerHeight() + $j("#content-1-enc").outerHeight();
                    //ajuste_y += (3 + 2.4) ;
                    if( group_count > 0 ){
                        for ( x = 1; x <= group_count; x++ ){
                            ajuste_y += $j("#header-group-"+x).outerHeight() + $j("#header-group-"+x+"-enc").outerHeight();
                        }
                        //ajuste_y += (group_count + 2.4);
                    } 
                }

                break;
        }

        return ajuste_y;
    }

    function update_properties(seccion, id, tipo){
        var offset = $j("#"+id).offset();
        ajuste_y = get_ajuste_y(seccion);

        rpt_objs[seccion][id]['y'] = (offset.top + $j('#dd-rpt-area').scrollTop() ) - offset_ajuste_top;
        rpt_objs[seccion][id]['x'] = (offset.left + $j('#dd-rpt-area').scrollLeft() ) - offset_ajuste_left;
        //$j(debug(offset.left+' '+offset_ajuste_left));

        var y = ((rpt_objs[seccion][id]['y'] - ajuste_y) * px_mm_y).toFixed(2);
        rpt_objs[seccion][id]['x'] = (rpt_objs[seccion][id]['x'] * px_mm_x).toFixed(2);

        if( y < 0){
            y = 0;
        }
        rpt_objs[seccion][id]['y'] = y;

        rpt_objs[seccion][id]['width'] = ($j("#"+id).width() * px_mm_x).toFixed(2);
        rpt_objs[seccion][id]['height'] = ($j("#"+id).height() * px_mm_y).toFixed(2);

        $j('#'+id+'_top').val(rpt_objs[seccion][id]['y']);
        $j('#'+id+'_left').val(rpt_objs[seccion][id]['x']);
        $j('#'+id+'_width').val(rpt_objs[seccion][id]['width']);
        $j('#'+id+'_height').val(rpt_objs[seccion][id]['height']);
    }

    //Muestra las propiedades de cada 
    function view_properties(seccion, id, tipo){
        $j('#dd-panel-objects').tabs('select','#obj-prop');
        $j('#obj-prop').html('');
        $j('#obj-prop').append('<table id="prop_'+id+'"></table>');

        var offset = $j("#"+id).offset();

        var asignar = 0;
        if( typeof(rpt_objs[seccion][id]['y']) == 'undefined'){
            rpt_objs[seccion][id]['y'] = (offset.top + $j('#dd-rpt-area').scrollTop()) - offset_ajuste_top;
            asignar = 1;
        }
        //$j(debug(asignar));

        var ajuste_y = get_ajuste_y(seccion);

        var y = ((rpt_objs[seccion][id]['y'] - ajuste_y) * px_mm_y).toFixed(2);
        if( y < 0){
            y = 0;
        }
        if( asignar == 1 ){
            rpt_objs[seccion][id]['y'] = y;
            rpt_objs[seccion][id]['x'] = (offset.left + $j('#dd-rpt-area').scrollLeft()) - offset_ajuste_left;
            rpt_objs[seccion][id]['x'] = (rpt_objs[seccion][id]['x'] * px_mm_x).toFixed(2);
            rpt_objs[seccion][id]['width'] = ($j("#"+id).width() * px_mm_x).toFixed(2);
            rpt_objs[seccion][id]['height'] = ($j("#"+id).height() * px_mm_y).toFixed(2);
        }

        /*
        Propiedades generales
        */
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td colspan="2">'+
                '<input type="button" id="'+id+'_borrar" class="btn btn_borrar" value="BORRAR" />'+
            '</td>'+
        '</tr>');
        $j('#'+id+'_borrar').bind("click",function(){
            remover_objeto(seccion, id);
        });

        //Top
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                'Top'+
                '</td>'+
            '<td>'+
                '<input size="12" type="text" id="'+id+'_top" value="'+rpt_objs[seccion][id]['y']+'" />'+
                '</td>'+
            '</tr>');
        $j('#'+id+'_top').bind("keyup",function(){

            var ajuste_y = get_ajuste_y(seccion);
            //$j(debug('Ob y:'+rpt_objs[seccion][id]['y'] ));

            var y = ((rpt_objs[seccion][id]['y'] - ajuste_y) * px_mm_y).toFixed(2);

            //$j(debug('Ob ny:'+y ));

            if( y < 0){
                y = 0;
            }

            //rpt_objs[seccion][id]['y'] = y;
            //var _top = (($j(this).val() / px_mm_y) - $j('#working_area').scrollTop()) + (offset_ajuste_top + ajuste_y);
            var _top = (($j(this).val() / px_mm_y) - $j('#dd-rpt-area').scrollTop()) + (offset_ajuste_top + ajuste_y);
            _top = _top.toFixed(2);

            var _offset = $j("#"+id).offset();

            if( _offset.top.toFixed(2) != _top ){
                $j('#'+id).offset({top: _top });
                rpt_objs[seccion][id]['y'] = $j(this).val();

                //$j(debug('direfente '+ _offset.top.toFixed(2)+' '+_top));
            }


            //$j(debug('Ob y:'+rpt_objs[seccion][id]['y'] ));
            //$j(debug('----------'));
        });

        //Left
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                'Left'+
                '</td>'+
            '<td>'+
                '<input size="12" type="text" id="'+id+'_left" value="'+rpt_objs[seccion][id]['x']+'" />'+
                '</td>'+
            '</tr>');
        $j('#'+id+'_left').bind("keyup",function(){
            $j('#'+id).offset({left: ($j(this).val() / px_mm_x) + offset_ajuste_left});
            rpt_objs[seccion][id]['x'] = $j(this).val();
        });

        //Width
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                'Width'+
                '</td>'+
            '<td>'+
                '<input size="12" type="text" id="'+id+'_width" value="'+rpt_objs[seccion][id]['width']+'" />'+
                '</td>'+
            '</tr>');
        $j('#'+id+'_width').bind("keyup",function(){
            $j('#'+id).width($j(this).val() / px_mm_x);
            rpt_objs[seccion][id]['width'] = $j(this).val();
            $j("#"+id+' span').width($j('#'+id).width()-1);
        });

        //Height
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                'Height'+
                '</td>'+
            '<td>'+
                '<input size="12" type="text" id="'+id+'_height" value="'+rpt_objs[seccion][id]['height']+'" />'+
            '</td>'+
            '</tr>');
        $j('#'+id+'_height').bind("keyup",function(){
            $j('#'+id).height($j(this).val() / px_mm_y);
            rpt_objs[seccion][id]['height'] = $j(this).val();
            $j("#"+id+' span').height($j('#'+id).height()-1);
        });

        //Align
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                '%PROP_ALINEACION%'+
                '</td>'+
            '<td>'+
                get_aligns(seccion, id)+
            '</td>'+
            '</tr>');
        $j('#'+id+'_align').bind("change",function(){
            rpt_objs[seccion][id]['align'] = $j(this).val();
            switch($j(this).val()){
                case "L":
                    $j('#'+id+' span').css({'text-align':'left'});
                    break;

                case "C":
                    $j('#'+id+' span').css({'text-align':'center'});
                    break;

                case "R":
                    $j('#'+id+' span').css({'text-align':'right'});
                    break;
            }
        });

        //Font-family
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                'Font-family'+
                '</td>'+
            '<td>'+
                get_fonts(seccion, id)+
            '</td>'+
            '</tr>');
        $j('#'+id+'_font_family').bind("change",function(){
            rpt_objs[seccion][id]['font-family'] = $j(this).val();
            $j('#'+id+' span').css({'font-family':$j(this).val()});
        });

        //Font-Size
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td>'+
                'Size'+
                '</td>'+
            '<td>'+
                '<input size="12" type="text" id="'+id+'_font_size" value="'+rpt_objs[seccion][id]['font-size']+'" />'+
            '</td>'+
            '</tr>');
        $j('#'+id+'_font_size').bind("keyup",function(){
            rpt_objs[seccion][id]['font-size'] = jQuery.trim($j(this).val().replace('pt',''));
            $j('#'+id+' span').css({'font-size':rpt_objs[seccion][id]['font-size']+'pt'});
        });

        //Font-color
        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td valign="top">'+
                'Fore-Color'+
                '</td>'+
            '<td>'+
                '<div id="'+id+'_color_picker" style="background-color:rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+');display:block;width:25px;height:25px;cursor:pointer;border:1px solid;"></div>'+
            '</td>'+
        '</tr> '+
        '<tr> '+
            '<td>R:</td><td><input size="4" type="text" id="'+id+'_font_color_r" value="'+rpt_objs[seccion][id]['font-color-R']+'" /></td>'+
        '</tr> '+
        '<tr> '+
            '<td>G:</td><td><input size="4" type="text" id="'+id+'_font_color_g" value="'+rpt_objs[seccion][id]['font-color-G']+'" /></td>'+
        '</tr> '+
        '<tr> '+
            '<td>B:</td><td><input size="4" type="text" id="'+id+'_font_color_b" value="'+rpt_objs[seccion][id]['font-color-B']+'" /></td>'+
        '</tr>');
        //Color picker
        $j('#'+id+'_color_picker').ColorPicker({
            color:$j('#'+id+'_color_picker').css('backgroundColor'),
            onChange: function (hsb, hex, rgb) {
                $j('#'+id+'_font_color_r').val(rgb.r);
                $j('#'+id+'_font_color_g').val(rgb.g);
                $j('#'+id+'_font_color_b').val(rgb.b);

                rpt_objs[seccion][id]['font-color-R'] = rgb.r;
                rpt_objs[seccion][id]['font-color-G'] = rgb.g;
                rpt_objs[seccion][id]['font-color-B'] = rgb.b;

                $j('#'+id+'_color_picker').css({'backgroundColor':'rgb(' + rgb.r + ', '+ rgb.g + ', '+ rgb.b + ')'});
                $j('#'+id+' span').css({'color':'rgb(' + rgb.r + ', '+ rgb.g + ', '+ rgb.b + ')'});
            }

        });
        //Valores RGB
        $j('#'+id+'_font_color_r').bind("keyup",function(){
            $j('#'+id+' span').css({'color':'rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+')'});
            $j('#'+id+'_color_picker').css({'backgroundColor':'rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+')'});
            rpt_objs[seccion][id]['font-color-R'] = $j(this).val();
        });
        $j('#'+id+'_font_color_g').bind("keyup",function(){
            $j('#'+id+' span').css({'color':'rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+')'});
            $j('#'+id+'_color_picker').css({'backgroundColor':'rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+')'});
            rpt_objs[seccion][id]['font-color-G'] = $j(this).val();
        });
        $j('#'+id+'_font_color_b').bind("keyup",function(){
            $j('#'+id+' span').css({'color':'rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+')'});
            $j('#'+id+'_color_picker').css({'backgroundColor':'rgb('+rpt_objs[seccion][id]['font-color-R']+','+rpt_objs[seccion][id]['font-color-G']+','+rpt_objs[seccion][id]['font-color-B']+')'});
            rpt_objs[seccion][id]['font-color-B'] = $j(this).val();
        });

        //BOLD ITALIC UNDERLINE
        bold_checked = '';
        if( rpt_objs[seccion][id]['font-bold'] == 'B' ){
            bold_checked = 'checked';
        }

        underline_checked = '';
        if( rpt_objs[seccion][id]['font-underline'] == 'U' ){
            underline_checked = 'checked';
        }

        italic_checked = '';
        if( rpt_objs[seccion][id]['font-italic'] == 'I' ){
            italic_checked = 'checked';
        }

        $j('#prop_'+id).append(''+
        '<tr>'+
            '<td colspan="2">'+
            '<div class="text_bold ui-widget-content-dd-tool ui-draggable"><input type="checkbox" id="'+id+'_bold" name="'+id+'_bold" value="B" '+bold_checked+' /></div>'+
            '<div class="text_underline ui-widget-content-dd-tool ui-draggable"><input type="checkbox" id="'+id+'_underline" name="'+id+'_underline" value="U" '+underline_checked+' /></div>'+
            '<div class="text_italic ui-widget-content-dd-tool ui-draggable"><input type="checkbox" id="'+id+'_italic" name="'+id+'_italic" value="I" '+italic_checked+' /></div>'+
            '</td>'+
            '</tr>');
        $j('#'+id+'_bold').bind("click",function(){
            rpt_objs[seccion][id]['font-bold'] = $j(this).val();
            if( $j(this).is(':checked') ){
                $j('#'+id+' span').css({'font-weight':'bold'});
            }else{
                $j('#'+id+' span').css({'font-weight':''});
            }
        });
        $j('#'+id+'_underline').bind("click",function(){
            rpt_objs[seccion][id]['font-underline'] = $j(this).val();
            if( $j(this).is(':checked') ){
                $j('#'+id+' span').css({'text-decoration':'underline'});
            }else{
                $j('#'+id+' span').css({'text-decoration':''});
            }
        });
        $j('#'+id+'_italic').bind("click",function(){
            rpt_objs[seccion][id]['font-italic'] = $j(this).val();
            if( $j(this).is(':checked') ){
                $j('#'+id+' span').css({'font-style':'italic'});
            }else{
                $j('#'+id+' span').css({'font-style':''});
            }
        });


        /*
        Propiedades especificas de cada tipo
        */
        switch(tipo){
            case 'barcode':
                //BARCODE
                $j('#prop_'+id).append(''+
                '<tr>'+
                    '<td>'+
                        'Campo'+
                        '</td>'+
                    '<td>'+
                        '<select type="text" id="'+id+'_field" >'+
                            get_fields(seccion, id)+
                            '</select>'+
                        '</td>'+
                    '</tr>');
                $j('#'+id+'_field').bind("change",function(){
                    $j('#'+id+' span').html($j(this).val());
                    rpt_objs[seccion][id]['field'] = $j(this).val();
                });
                break;

            case 'fx':
                //FX
                rpt_objs[seccion][id]['txt'] = $j("#"+id+' span').html();
                $j('#prop_'+id).append(''+
                '<tr>'+
                    '<td colspan="2">'+
                    '<input class="btn fx" type="button" id="'+id+'_btn_dialog_txt" onclick="$j(\'#'+id+'_dialog_txt\').dialog(\'open\');" value="Expresion ..." />'+
                        '</td>'+
                    '</tr>'+
                    '<tr>'+
                    '<td colspan="2">'+
                        '<div id="'+id+'_dialog_txt" title="Expresion" >'+
                            '<select >'+
                                get_fields(seccion, id)+
                            '</select>'+
                            '<textarea id="'+id+'_txt" style="width:100%;height:90%;">'+rpt_objs[seccion][id]['txt']+'</textarea>'+
                        '</div>'+
                    '</td>'+
                    '</tr>');
                $j('#'+id+'_txt').bind("keyup",function(){
                    $j('#'+id+' span').html($j(this).val());
                    rpt_objs[seccion][id]['txt'] = $j(this).val();
                });

                $j('#'+id+'_dialog_txt').dialog({
                            bgiframe: true,
                            autoOpen: false,
                            height: 600,
                            width: 600,
                            modal: true
                        });
                
                break;
            case 'label':
                //Texto
                rpt_objs[seccion][id]['txt'] = $j("#"+id+' span').html();
                $j('#prop_'+id).append(''+
                '<tr>'+
                    '<td>'+
                        'Texto'+
                        '</td>'+
                    '<td>'+
                        '<input size="12" type="text" id="'+id+'_txt" value="'+rpt_objs[seccion][id]['txt']+'" />'+
                        '</td>'+
                    '</tr>');
                $j('#'+id+'_txt').bind("keyup",function(){
                    $j('#'+id+' span').html($j(this).val());
                    rpt_objs[seccion][id]['txt'] = $j(this).val();
                });
                break;

            case 'field':
                //Campo
                $j('#prop_'+id).append(''+
                '<tr>'+
                    '<td>'+
                        'Campo'+
                        '</td>'+
                    '<td>'+
                        '<select type="text" id="'+id+'_field" >'+
                            get_fields(seccion, id)+
                            '</select>'+
                        '</td>'+
                    '</tr>');
                $j('#'+id+'_field').bind("change",function(){
                    $j('#'+id+' span').html($j(this).val());
                    rpt_objs[seccion][id]['field'] = $j(this).val();
                });
                break;
        }

        hideWaitWindow();
    }

    //Muestra las propiedades de cada seccion
    function view_seccion_properties(seccion, id){
        $j('#dd-panel-objects').tabs('select','#obj-prop');
        $j('#obj-prop').html('');
        $j('#obj-prop').append('<table id="prop_'+seccion+'"></table>');
        $j('#'+id).height(rpt_secs[seccion]['height'] / px_mm_y);

        $j('#prop_'+seccion).append(''+
        '<tr>'+
            '<td>'+
                'Sección'+
                '</td>'+
            '<td>'+
                '<b>'+seccion+'</b>'+
                '</td>'+
            '</tr>'+
        '<tr>'+
            '<td>'+
                'Height'+
                '</td>'+
            '<td>'+
                '<input size="12" type="text" id="'+seccion+'_height" value="'+rpt_secs[seccion]['height']+'" />'+
                '</td>'+
            '</tr>');
        $j('#'+seccion+'_height').bind("keyup",function(){
            if(seccion == 'header' || seccion == 'content' || seccion == 'footer'){
                $j('#'+seccion+'-1').height($j(this).val() / px_mm_y);
            }else{
                $j('#'+seccion).height($j(this).val() / px_mm_y);
            }
            rpt_secs[seccion]['height'] = $j(this).val();

        });

        switch(seccion){
            case "header":
                break;

            case "content":
                $j('#prop_'+seccion).append(''+
                '<tr>'+
                    '<td>'+
                        'Sql Alias'+
                        '</td>'+
                    '<td>'+
                        get_alias(seccion)+
                        '</td>'+
                    '</tr>');
                $j('#'+seccion+'_sql_alias').bind("change",function(){
                    rpt_secs[seccion]['sql_alias'] = $j(this).val();
                });
                break;

            case "footer":
                break;

            default:
                var re = /^header-group(.*)/;
                if( re.test(seccion) ){
                    //Opcion para seleccionar el alias
                    $j('#prop_'+seccion).append(''+
                    '<tr>'+
                        '<td>'+
                            'Sql Alias'+
                            '</td>'+
                        '<td>'+
                            get_alias(seccion)+
                            '</td>'+
                        '</tr>');
                    $j('#'+seccion+'_sql_alias').bind("change",function(){
                        rpt_secs[seccion]['sql_alias'] = $j(this).val();
                    });

                    //opcion para seleccionar que campos e usara para agrupar
                    $j('#prop_'+seccion).append(''+
                    '<tr>'+
                        '<td>'+
                            'Agrupar por'+
                            '</td>'+
                        '<td>'+
                            get_fields_group_by(seccion)+
                            '</td>'+
                        '</tr>');
                    $j('#'+seccion+'_group_by').bind("change",function(){
                        rpt_secs[seccion]['group_by'] = $j(this).val();
                    });
                }

                var re = /^footer-group(.*)/;
                if( re.test(seccion) ){
                    //Opcion para seleccionar el alias
                    $j('#prop_'+seccion).append(''+
                    '<tr>'+
                        '<td>'+
                            'Imprimir desde:'+
                            '</td>'+
                        '<td>'+
                            get_print_from_details(seccion)+
                        '</td>'+
                    '</tr> '+
                    '<tr id="custom_top_start"> '+
                        '<td> '+
                        '%CUSTOM_TOP_START%'+
                        '</td> '+
                        '<td> '+
                            '<input size="12" type="text" id="'+seccion+'_custom_top_start" value="'+rpt_secs[seccion]['custom_top_start']+'" />'+
                        '</td> '+
                    '</tr> ');

                    if( rpt_secs[seccion]['print_from'] == 'end' ){
                        $hide('custom_top_start');
                    }else{
                        $show('custom_top_start');
                    }

                    $j('#'+seccion+'_custom_top_start').bind("keyup",function(){
                            rpt_secs[seccion]['custom_top_start'] = $j(this).val();
                    });

                    $j('#'+seccion+'_print_from').bind("change",function(){
                        if( $j(this).val() == 'end' ){
                            $hide('custom_top_start');
                        }else{
                            $show('custom_top_start');
                        }
                        rpt_secs[seccion]['print_from'] = $j(this).val();
                    });
                }
                break;

        }

    }

    //Obtiene la lista de las consultas registradas
    function get_alias(seccion){
        
        var options = '<select id="'+seccion+'_sql_alias" >';
        options += '<option value="">%SELECCIONE%</option>';
        for(consulta in rpt_sql){
            var selected = '';
            if(rpt_secs[seccion]['sql_alias'] == consulta){
                selected = 'selected';
            }
            options += '<option value="'+consulta+'" '+selected+' >'+consulta+'</option>';
        } 
        options += '</select>';
        return options;
    }

    //Muestra la ventana para administrar las consultas
    function view_consultas_admin(){
        var lista_alias = '';
        for(id in rpt_sql){
            if(lista_alias != ''){
                lista_alias += ',';
            } 
            lista_alias += id;
        }
        lista_alias = 'lista_alias='+lista_alias;
        wnd_consultas = _window({mvc:'DeliriumDesigner::view_consultas_admin', mvcparams:lista_alias, title:'Querys', width:$j(window).width()-20, height:300, model:true});
    }

    //Borra un objeto dell reporte
    function remover_objeto(seccion, id){
        $j('#obj-prop').html('');
        delete(rpt_objs[seccion][id]);
        $j('#'+id).remove();
    }

    //Agrega una seccion de agrupamiento
    function agregar_seccion_agrupamiento(){
        //Incrementar el numero de grupos
        group_count++;

        $j('#content-1-enc').before(' ' +
            '<div id="header-group-'+group_count+'-enc" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix"> ' +
            '    <input onclick="jQuery(view_seccion_properties(\'header-group-'+group_count+'\', \'header-group-'+group_count+'\'))" type="button" value="" class="btn application_edit"/>  ' +
            '    %ENCABEZADO_GRUPO% '+group_count+'' +
            '</div> ' +
            '<div id="header-group-'+group_count+'" class="rpt-container rpt-header-group"></div> ' +
        '');
        $j('#content-1').after(' ' +
           ' <div id="footer-group-'+group_count+'-enc" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix"> ' +
           '     <input onclick="jQuery(view_seccion_properties(\'footer-group-'+group_count+'\', \'footer-group-'+group_count+'\' ))" type="button" value="" class="btn application_edit"/>  ' +
           '     %PIE_GRUPO% ' +
           ' </div> ' +
           ' <div id="footer-group-'+group_count+'" class="rpt-container rpt-footer-group"></div> ' +
        '');

        //Habilitar el resize de la seccion recien agregada
        $j('#footer-group-'+group_count+', #header-group-'+group_count).resizable({
            stop : function(event, ui){
                var id = $j(this).attr('id');
                rpt_secs[id]['height'] = ($j("#"+id).height() * px_mm_y).toFixed(2);
                jQuery(view_seccion_properties(id, id));

                //Ajustar cooredenadas de los objetos despues de hacer el resize de alguna sección
                showWaitWindow("%LBL_ESPERE%","%LBL_ESPERE%");
                for(seccion in rpt_objs){
                    for(indice in rpt_objs[seccion]) {
                        jQuery(view_properties(seccion, rpt_objs[seccion][indice]['id'], rpt_objs[seccion][indice]['tipo']));
                    }
                }
                hideWaitWindow();
            },
            handles : 's'
        });

        //Marcar la seccion para recibir objetos dragables
        $j('#footer-group-'+group_count+', #header-group-'+group_count+'').droppable(
            {
                drop : function(event, ui){
                    seccion = "";
                    if($j(this).hasClass("rpt-header-group")){
                        seccion = $j(this).attr('id'); //"header-group-"+group_count; 
                    }

                    if($j(this).hasClass("rpt-footer-group")){
                        seccion = $j(this).attr('id'); //"header-group-"+group_count; 
                        //seccion = "footer-group-"+group_count; 
                    }

                    jQuery(crear_objeto(this, ui, seccion));
                } 
            }
        ).height((50 / px_mm_y));

        //Inicializar secciones
        rpt_secs['header-group-'+group_count] = [];
        rpt_secs['footer-group-'+group_count] = [];
        
        rpt_secs['header-group-'+group_count]['height'] = rpt_secs['footer-group-'+group_count]['height'] = 50; 
        
        rpt_secs['header-group-'+group_count]['print_from'] = rpt_secs['footer-group-'+group_count]['print_from'] = 'end';

        rpt_secs['footer-group-'+group_count]['custom_top_start'] = 0;
            
        //Inicializar array de objetos
        rpt_objs['header-group-'+group_count] = []; 
        rpt_objs['footer-group-'+group_count] = []; 

    }

    //Muestra un preview del documento
    function preview(){
        $j("#frm_preview_parametros").html("");

        for(prop in rpt_props){
            $j("#frm_preview_parametros").append('<input type="text" id="rpt_props['+prop+']" name="rpt_props['+prop+']" value="'+rpt_props[prop]+'" /><br />');
        }

        $j("#frm_preview_parametros").append('<input type="text" id="header_height" name="header_height" value="'+rpt_secs['header']['height']+'" /><br />');
        $j("#frm_preview_parametros").append('<input type="text" id="content_height" name="content_height" value="'+rpt_secs['content']['height']+'" /><br />');
        $j("#frm_preview_parametros").append('<input type="text" id="footer_height" name="footer_height" value="'+rpt_secs['footer']['height']+'" /><br />');
        $j("#frm_preview_parametros").append('<input type="text" id="content_alias" name="content_alias" value="'+rpt_secs['content']['sql_alias']+'" /><br />');

        $j("#frm_preview_parametros").append('<input type="text" id="rpt_props[group_count]" name="rpt_props[group_count]" value="'+group_count+'" /><br />');
        for(seccion in rpt_secs){
            $j("#frm_preview_parametros").append('<input type="text" id="seccion[]" name="seccion[]" value="'+seccion+'" /><br />');
            for(prop in rpt_secs[seccion]) {
                $j("#frm_preview_parametros").append('<input type="text" id="seccion['+seccion+']['+prop+']" name="seccion['+seccion+']['+prop+']" value="'+rpt_secs[seccion][prop]+'" /><br />');
            }
        }

        for(seccion in rpt_objs){
            for(indice in rpt_objs[seccion]) {
            $j("#frm_preview_parametros").append('<input type="text" id="'+seccion+'[]" name="'+seccion+'[]" value="'+rpt_objs[seccion][indice]['id']+'" /><br />');
                for(prop in rpt_objs[seccion][indice]){
                    $j("#frm_preview_parametros").append('<input type="text" id="'+rpt_objs[seccion][indice]['id']+'['+prop+']" name="'+rpt_objs[seccion][indice]['id']+'['+prop+']" value="'+rpt_objs[seccion][indice][prop]+'" /><br />');
                }
                $j("#frm_preview_parametros").append('<br /><br />');
            }
        }

        for(consulta in rpt_sql){
            $j("#frm_preview_parametros").append('<hr /><input type="text" id="sql[]" name="sql[]" value="'+consulta+'" /><br />');
            $j("#frm_preview_parametros").append('<input type="text" id="'+consulta+'[sql]" name="'+consulta+'[sql]" value="'+rpt_sql[consulta]['sql']+'" /><br />');
        }
        var ac = $j('#frm_preview').attr("action");
        var ac = $j('#frm_preview').attr("action",ac +'1');
        $j("#frm_preview").submit();
    }

    //Registra el documento
    function save(){
        $j("#frm_save_parametros").html("");

        for(prop in rpt_props){
            $j("#frm_save_parametros").append('<input type="text" id="rpt_props['+prop+']" name="rpt_props['+prop+']" value="'+rpt_props[prop]+'" />');
        }

        $j("#frm_save_parametros").append('<input type="text" id="header_height" name="header_height" value="'+rpt_secs['header']['height']+'" /><br />');
        $j("#frm_save_parametros").append('<input type="text" id="content_height" name="content_height" value="'+rpt_secs['content']['height']+'" /><br />');
        $j("#frm_save_parametros").append('<input type="text" id="footer_height" name="footer_height" value="'+rpt_secs['footer']['height']+'" /><br />');
        $j("#frm_save_parametros").append('<input type="text" id="content_alias" name="content_alias" value="'+rpt_secs['content']['sql_alias']+'" /><br />');

        $j("#frm_save_parametros").append('<input type="text" id="rpt_props[group_count]" name="rpt_props[group_count]" value="'+group_count+'" /><br />');
        for(seccion in rpt_secs){
            $j("#frm_save_parametros").append('<input type="text" id="seccion[]" name="seccion[]" value="'+seccion+'" /><br />');
            for(prop in rpt_secs[seccion]) {
                $j("#frm_save_parametros").append('<input type="text" id="seccion['+seccion+']['+prop+']" name="seccion['+seccion+']['+prop+']" value="'+rpt_secs[seccion][prop]+'" /><br />');
            }
        }

        for(seccion in rpt_objs){
            for(indice in rpt_objs[seccion]) {
                $j("#frm_save_parametros").append('<input type="text" id="'+seccion+'[]" name="'+seccion+'[]" value="'+rpt_objs[seccion][indice]['id']+'" />');
                for(prop in rpt_objs[seccion][indice]){
                    $j("#frm_save_parametros").append('<input type="text" id="'+rpt_objs[seccion][indice]['id']+'['+prop+']" name="'+rpt_objs[seccion][indice]['id']+'['+prop+']" value="'+rpt_objs[seccion][indice][prop]+'" />');
                }
                $j("#frm_save_parametros").append('<br />');
            }
        }

        for(consulta in rpt_sql){
            $j("#frm_save_parametros").append('<input type="text" id="sql[]" name="sql[]" value="'+consulta+'" />');
            $j("#frm_save_parametros").append('<input type="text" id="'+consulta+'[sql]" name="'+consulta+'[sql]" value="'+rpt_sql[consulta]['sql']+'" />');
        }

        var ac = $j('#frm_save').attr("action");
        var ac = $j('#frm_save').attr("action",ac +'1');
        $j("#frm_save").submit();
    }

    //Exporta el documento
    function exportar(){
        $j("#frm_export_parametros").html("");

        for(prop in rpt_props){
            $j("#frm_export_parametros").append('<input type="text" id="rpt_props['+prop+']" name="rpt_props['+prop+']" value="'+rpt_props[prop]+'" />');
        }

        $j("#frm_export_parametros").append('<input type="text" id="header_height" name="header_height" value="'+rpt_secs['header']['height']+'" /><br />');
        $j("#frm_export_parametros").append('<input type="text" id="content_height" name="content_height" value="'+rpt_secs['content']['height']+'" /><br />');
        $j("#frm_export_parametros").append('<input type="text" id="footer_height" name="footer_height" value="'+rpt_secs['footer']['height']+'" /><br />');
        $j("#frm_export_parametros").append('<input type="text" id="content_alias" name="content_alias" value="'+rpt_secs['content']['sql_alias']+'" /><br />');

        $j("#frm_export_parametros").append('<input type="text" id="rpt_props[group_count]" name="rpt_props[group_count]" value="'+group_count+'" /><br />');
        for(seccion in rpt_secs){
            $j("#frm_export_parametros").append('<input type="text" id="seccion[]" name="seccion[]" value="'+seccion+'" /><br />');
            for(prop in rpt_secs[seccion]) {
                $j("#frm_export_parametros").append('<input type="text" id="seccion['+seccion+']['+prop+']" name="seccion['+seccion+']['+prop+']" value="'+rpt_secs[seccion][prop]+'" /><br />');
            }
        }

        for(seccion in rpt_objs){
            for(indice in rpt_objs[seccion]) {
                $j("#frm_export_parametros").append('<input type="text" id="'+seccion+'[]" name="'+seccion+'[]" value="'+rpt_objs[seccion][indice]['id']+'" />');
                for(prop in rpt_objs[seccion][indice]){
                    $j("#frm_export_parametros").append('<input type="text" id="'+rpt_objs[seccion][indice]['id']+'['+prop+']" name="'+rpt_objs[seccion][indice]['id']+'['+prop+']" value="'+rpt_objs[seccion][indice][prop]+'" />');
                }
                $j("#frm_export_parametros").append('<br />');
            }
        }

        for(consulta in rpt_sql){
            $j("#frm_export_parametros").append('<input type="text" id="sql[]" name="sql[]" value="'+consulta+'" />');
            $j("#frm_export_parametros").append('<input type="text" id="'+consulta+'[sql]" name="'+consulta+'[sql]" value="'+rpt_sql[consulta]['sql']+'" />');
        }

        var ac = $j('#frm_export').attr("action");
        var ac = $j('#frm_export').attr("action",ac +'1');
        $j("#frm_export").submit();
    }

    //FIN CATCH
    }catch(x){debug(x);}

</script>

    <style>
        .ui-widget-content-dd-tool{ 
            width: 30; 
            height: 30; 
            padding: 0.5em; 
            display:block;
            float: left; 
            margin: 0 10px 10px 0; 
        }

        .rpt-item span{
            width:100px;
            height:30px;
            display:block;
            overflow:hidden;
            white-space:normal;
            border: 1px dotted;
            cursor:pointer;
        }

        #header-1{
            display:block;
            height: 200px;
            width:100%;
        }

        .ui-resizable-handle{
            z-index:400 !important;
        }

        .ui-dialog, .ui-dialog-content, .ui-accordion .ui-accordion-content{
            padding:0px;
        }
    </style>
    <div id="rpt-menu">
        <div id="rpt-menu-buttons">
            <input onclick="agregar_seccion_agrupamiento()" type="button" class="dd-tool btn_agregar" value="Agregar cabecera y pie de grupo" />
            <input id="btn_vars" type="button" class="dd-tool var" value="%VARS%" onclick="$j('#frm-rpt-vars').dialog('open');"/>
            <input id="btn_consultas" type="button" class="dd-tool database_table" value="SQL" />
            <input id="btn_preview" type="button" class="dd-tool report" value="PREVIEW" />
            <input id="btn_save" type="button" class="dd-tool database_save" value="GUARDAR" />
            <input id="btn_exportar" type="button" class="dd-tool database_gear" value="EXPORTAR" />

            <div styel="clear:both;"></div>
        </div>
    </div>
    <div id="delirium-designer">
        <div id="dd-panel-objects" >
            <div>
                <div id="label" class="ui-widget-content-dd-tool">
                    <a class="dd-tool label"><span>label</span></a>
                </div>
                <div id="textfield" class="ui-widget-content-dd-tool">
                    <a class="dd-tool textfield"><span>textfield</span></a>
                </div>
                <div id="fx" class="ui-widget-content-dd-tool">
                    <a class="dd-tool fx"><span>fx</span></a>
                </div>
                <div id="barcode" class="ui-widget-content-dd-tool">
                    <a class="dd-tool barcode"><span>fx</span></a>
                </div>
                <!--<div id="picture" class="ui-widget-content-dd-tool">-->
                    <!--<a class="dd-tool picture"><span>pict</span></a>-->
                <!--</div>-->
                <div class="clear"></div>
            </div>
            <ul>
                <li>
                    <a href="#tab-documento" class="lbl_tab">%PROPIEDADES_DOCUMENTO%</a>
                </li>
                <li>
                    <a href="#obj-prop" class="lbl_tab">%PROPIEDADES_OBJETO%</a>
                </li>
            </ul>
            <div>
                <div id="tab-documento">
                    <table style="width:100%" class="dd-props">
                        <tr>
                            <td>
                                %UUID%
                            </td>
                            <td>
                                <input type="text" id="uuid" name="uuid" value="rpt" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                %UUID_MODULO%
                            </td>
                            <td>
                                <input type="text" id="uuid_modulo" name="uuid_modulo" value="rpt_modulo" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                %TITULO%
                            </td>
                            <td>
                                <input type="text" id="chr_titulo" name="chr_titulo" value="titulo" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                %WIDTH%
                            </td>
                            <td>
                                <input type="text" id="width" name="width" value="0" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                %HEIGHT%
                            </td>
                            <td>
                                <input type="text" id="height" name="height" value="0" />
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="obj-prop" class="dd-props"></div>
            </div>
        </div>
        <div id="dd-rpt-area">
            <div id="dd-panel-rpt">
                <div id="header-1-enc" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                    <input onclick="jQuery(view_seccion_properties('header','header-1'))" type="button" id="ep1-d" name="ep1-d" value="" class="btn application_edit"/> 
                    %ENCABEZADO_PAGINA%
                </div>
                <div id="header-1" class="rpt-container rpt-header"></div>

                <div id="content-1-enc" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                    <input onclick="jQuery(view_seccion_properties('content','content-1'))" type="button" id="ep1-d" name="ep1-d" value="" class="btn application_edit"/> 
                    %CONTENIDO%
                </div>
                <div id="content-1" class="rpt-container rpt-content"></div>

                <div id="footer-1-enc" class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                    <input onclick="jQuery(view_seccion_properties('footer','footer-1'))" type="button" id="ep1-d" name="ep1-d" value="" class="btn application_edit"/> 
                    %PIE_PAGINA%
                </div>
                <div id="footer-1" class="rpt-container rpt-footer"></div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
    <div id="frm-rpt-vars">
        <table width="100%" class="default_search">
            <tr>
                <th>
                    %VAR_NAME%
                </th>
                <th>
                    %VAR_TYPE%
                </th>
                <th>
                    %VAR_VALUE%
                </th>
            </tr>
            <tr>
                <th>
                    %VAR_NAME%
                </th>
                <th>
                    %VAR_TYPE%
                </th>
                <th>
                    %VAR_VALUE%
                    <input class="btn btn_add" type="button" value="%VAR_AGREGAR%" />
                </th>
            </tr>
        </table>
    </div>
    <div class="hide">
        <form id="frm_preview" method="POST" action="helper.php?" _onstart="" _do="" target="_blank" >
            <input type="hidden" id="do" name="do" value="DeliriumDesigner::preview">
            <input type="hidden" id="save" name="save" value="0" />
            <div id="frm_preview_parametros">
            </div>
        </form>
        <form id="frm_save" method="POST" action="helper.php?" _onstart="" _do="" target="_blank">
            <input type="hidden" id="do" name="do" value="DeliriumDesigner::preview">
            <input type="hidden" id="save" name="save" value="1" />
            <div id="frm_save_parametros">
            </div>
        </form>
        <form id="frm_export" method="POST" action="helper.php?" _onstart="" _do="" target="_blank">
            <input type="hidden" id="do" name="do" value="DeliriumDesigner::preview">
            <input type="hidden" id="save" name="save" value="2" />
            <div id="frm_export_parametros">
            </div>
        </form>
    </div>
