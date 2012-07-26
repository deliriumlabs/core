<?php
class Controller_CoreNotificaciones extends Controller_Base{
    function index(){
    }

    function listar_widget_mensajes(){
        $this->setModel('CoreNotificaciones');
        $template['lista_mensajes'] = $this->model->listar_widget_mensajes();
        $txt = '
            <div style="height:20px;padding:10px 0px;border-bottom:1px solid #D8DFEA;" >
                <div style="vertical-align: middle;">
                    <div style=" cursor: pointer; position: relative; text-align: center; top: -50%; z-index: 1; ">
                        <span class="texto-titulo">%LBL_NOTIFICACIONES%</span>
                        <div class="atras">
                            <div class="barra"></div>
                        </div>
                    </div>
                </div>
            </div>
            <ul>
                {{START lista_mensajes}} 
                <li id="li_notificacion_[id_msg]">
                    <a style="clear:both;width:1px;height:10px" class="btn_borrar" href="#" onclick="ocultar_notificacion([id_msg]);return false;"></a>
                    <a href="#" onclick="mostrar_notificacion([id_msg]);return false;">[chr_subject]</a>
                    <div style="clear:both;"></div>
                </li>
                {{END lista_mensajes}} 
            </ul>
            ';
        $this->renderText($txt, $template);
    }

    function mostrar_notificacion(){
        $this->setModel('CoreNotificaciones');
        $mensaje = $this->model->get_mensaje($_POST['id_msg']);

        $mensaje['hide_enlace'] = 'hide';
        if($mensaje['chr_enlace'] != ''){
            $mensaje['hide_enlace'] = '';
        }
        $this->renderTemplate('view_mensaje.tpl',$mensaje);
    }

    function ocultar_notificacion(){
        $this->setModel('CoreNotificaciones');
        $this->model->ocultar_notificacion($_POST['id_msg']);
    }
}
?>
