<?php
class Model_CoreNotificaciones{
    function listar_widget_mensajes(){
        $strSql = "
            SELECT
                *
            FROM
                mensajes_tbl_msg
            WHERE
                id_to = '{$_SESSION['id_usuario']}'
            AND
                bol_notificaciones = 1
            ORDER BY
                dtm_fecha DESC
            ";
        return query2array($strSql);
    }

    function get_mensaje($id_msg = 0){
        $strSql = "
            SELECT
                mensaje.id_msg, mensaje.chr_subject, mensaje.txt_body, DATE_FORMAT(dtm_fecha, '%d/%m/%Y') AS dtm_fecha_fto,
                mensaje.chr_enlace, 
                CONCAT_WS(' ', empleados.chr_nombres, empleados.chr_apaterno, empleados.chr_amaterno) AS chr_envia
            FROM
                mensajes_tbl_msg AS mensaje
            LEFT JOIN
                empleados_tbl_datos_generales AS empleados
                    ON empleados.id_usuario = mensaje.id_from
            WHERE
                id_msg = '$id_msg'
            ";
        return query2vars($strSql);
    }

    function ocultar_notificacion($id_msg){
        $strSql = "
            UPDATE
                mensajes_tbl_msg
            SET
                bol_notificaciones = 0
            WHERE
                id_msg = '$id_msg'
            ";
        query($strSql);
    }
}
?>
