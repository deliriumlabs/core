<?php
if(!class_exists('Model_DeliriumSearch')){
    
class Model_DeliriumSearch extends Model_Base {
	public static function search($datos){
        $profile = 0;
        $str_profile = '';
        $then = microtime(true);
		extract($datos);
        $DEBUG_QUERY = $debug ;
        $tabla=stripslashes($tabla);
		$array_campos=explode(',',$campos);
		$total_campos=sizeof($array_campos);
		$reg_por_pagina=$registros;
		//$strSelect="Select SQL_NO_CACHE  ";
		$strSelect='Select ';
		$strCampos='';
		$strFrom=' From ('.$tabla.') as t';
		$strWhere=' Where ';
		$strOrdenarPor = ' Order By ';		
		$black_list=array();
		$tmp=array();
		$tmp['datos']='';
		$col_span_titulo=0;
		$tmp['datos'].='<thead>';
        $tmp['datos'].='<tr>';
        $primer_campo= '';
        $campo_orden = '';
		//for( $i = 0; $i < $total_campos; ++$i){
        $i = 0;while( $i < $total_campos){
			$campo=explode('|',$array_campos[$i]);

			if($i>0){
				$strCampos.=',';
            }else{
                $primer_campo = $campo[1];
            }

			if($campo[3]=='moneda'){
                $strCampos.="CONCAT('<div
                style=\"text-align:right\">&#36;',TRIM(TRAILING '.' FROM
                TRIM(BOTH '0' FROM FORMAT({$campo[1]}, 3))),'</div>') as {$campo[1]}";
				//$strCampos.="CONCAT('<div style=\"text-align:right\">$', TRIM(LEADING '0' FROM FORMAT({$campo[1]}, 3)),'</div>') as {$campo[1]}";
			}else{
				//$strCampos.="({$campo[1]}) as {$campo[1]}";
				$strCampos.=$campo[1];
			}
			
			if(trim($campo[2])!=''){
				if($i>0&&$strOrdenarPor!=' Order By '){
					$strOrdenarPor.=',';
                }
                if($campo_orden == ''){
                    $campo_orden = $campo[1];
                }
				if($campo[5]=='true'){
					$strOrdenarPor.=" abs({$campo[1]}) {$campo[2]} ";
                }elseif($campo[3]=='fecha'){ 
					$strOrdenarPor.=" STR_TO_DATE({$campo[1]},'%d/%m/%Y') {$campo[2]} ";
                }else{
					$strOrdenarPor.=" {$campo[1]} {$campo[2]} ";
				}
			}
			
			if($campo[4]==1){
				
				if($excel==0){
					$tmp['datos'].='<th >';
					$tmp['datos'].="<a href=\"javascript:void(0);\" onclick=\"eval('$delirium_request').ordenar('{$campo[1]}')\">
										{$campo[0]}
									</a>";
				}else{
					$tmp['datos'].='<th>';
					$tmp['datos'].=$campo[0];
				}
				
				$col_span_titulo++;
			}else{
				$black_list[]=$i;
			}
            ++$i;
		}

        //Obtener el campo de ordenamiento con todo y el alias de la tabla
        //$pos = stripos($tabla, $primer_campo);
        $pos = stripos($tabla, $campo_orden);
        $fragmento =  substr($tabla, 0, $pos+strlen(trim($primer_campo)));
        $space_pos = strripos(trim($fragmento), ' ');
        $campo_orden=  substr($fragmento, $space_pos);


		$tmp['datos'].='</th>';
		if($excel==0&&trim($opciones!='')){
			$tmp['datos'].='<th>%OPCIONES%</th>';
		}
		$tmp['datos'].='</tr>';
		
		$tmp['datos'].='</thead>';
		
		//CREAR EL WHERE
		$array_where=explode(',',$where);
		$total_where=sizeof($array_where);
		if(trim($where)!=''){
			//for( $i= 0; $i < $total_where; ++$i){
            $i = 0;while( $i < $total_where){
				$campo=explode('|',$array_where[$i]);
                if ($campo[1] == 'entre' ){
                    $strWhere.=" STR_TO_DATE({$campo[0]}, '%d/%m/%Y') ";
                }else{
                    if($campo[3]=='fecha'){
                        $strWhere.=" STR_TO_DATE({$campo[0]}, '%d/%m/%Y') ";
                    }else{
                        $strWhere.=" $campo[0] ";
                    }
                }
				switch ($campo[1]){
					case 'igual':
						if($campo[3]=='fecha'){
                            //$fecha = $campo[2];
                            $fecha = date2mysql($campo[2]);
							$strWhere.=" = '$fecha'";
						}else{
							$strWhere.=" = '{$campo[2]}'";
						}
						break;
					case 'contenga':
						$strWhere.=" Like '%{$campo[2]}%' ";
						break;
					case 'inicie':
						$strWhere.=" Like '{$campo[2]}%' ";
						break;
					case 'termine';
						$strWhere.=" Like '%{$campo[2]}%' ";
						break;
					case 'entre':
						$valores=explode('*',$campo[2]);
						if($campo[3]=='fecha'){
                            /*
							$valores[0]=str_replace("-","/",$valores[0]);
							list($dia,$mes,$anio)=explode("/",$valores[0]);
							$fecha1="$anio-$mes-$dia";
							
							$valores[1]=str_replace("-","/",$valores[1]);
							list($dia,$mes,$anio)=explode("/",$valores[1]);
							$fecha2="$anio-$mes-$dia";
                            */

                            $fecha1 = $valores[0];
                            $fecha2 = $valores[1];

                            $fecha1 = date2mysql($valores[0]);
                            $fecha2 = date2mysql($valores[1]);
													
							$strWhere.=" between '$fecha1' and '$fecha2'";
						}else{
							$strWhere.=" between '{$valores[0]}' and '{$valores[1]}'";
						}
						break;
					
					case 'mayor':
                        if($campo[3]=='fecha'){
                            /*
							$campo[2]=str_replace("-","/",$campo[2]);
							list($dia,$mes,$anio)=explode("/",$campo[2]);
                            $fecha="$anio-$mes-$dia";
                            */
                            $fecha = $campo[2];
							$strWhere.=" > '$fecha'";
						}else{
							$strWhere.=" > '{$campo[2]}'";
						}
						break;
					
					case 'mayorigual':
						if($campo[3]=='fecha'){
							$campo[2]=str_replace('-','/',$campo[2]);
							list($dia,$mes,$anio)=explode('/',$campo[2]);
							$fecha="$anio-$mes-$dia";
                            $fecha = $campo[2];
							$strWhere.=" >= '$fecha'";
						}else{
							$strWhere.=" >= '{$campo[2]}'";
						}
						break;
					
					case 'menor':
						if($campo[3]=='fecha'){
							$campo[2]=str_replace('-','/',$campo[2]);
							list($dia,$mes,$anio)=explode('/',$campo[2]);
							$fecha="$anio-$mes-$dia";
                            $fecha = $campo[2];
							$strWhere.=" < '$fecha'";
						}else{
							$strWhere.=" < '{$campo[2]}'";
						}
						break;
					case 'menorigual':
						if($campo[3]=='fecha'){
							$campo[2]=str_replace('-','/',$campo[2]);
							list($dia,$mes,$anio)=explode('/',$campo[2]);
							$fecha="$anio-$mes-$dia";
                            $fecha = $campo[2];
							$strWhere.=" <= '$fecha'";
						}else{
							$strWhere.=" <= '{$campo[2]}'";
						}
						break;
				}
				$strWhere.=" {$campo[4]} ";
                ++$i;
			}
		}else{		
			$strWhere=' ';
		}
		
		if (!isset($pagina)) {
			$pagina=0;
		}
        $pos = stripos($tabla, 'SELECT ');
        if($pos === FALSE){
            $strSql = 'SELECT * '.$strFrom.' '.$strWhere;
        }else{
           //$tabla = str_ireplace("SELECT ", "SELECT SQL_NO_CACHE count($primer_campo) as _total_count, ", $tabla) ;
           //$strSqlCount = "SELECT SQL_NO_CACHE count($campo_orden) as _total_count ".stristr($tabla, 'FROM');
           $strSqlCount = "SELECT SQL_NO_CACHE count(*) as _total_count ".stristr($tabla, 'FROM');
        }
        //$strSql = "SELECT * $strFrom $strWhere";
        if(trim($strWhere) == ''){
            $strSql = $strSqlCount;
        }else{
            $strSql = "SELECT SQL_NO_CACHE count(*) as _total_count from ($tabla) as t $strWhere";
        }
        $strSql = "SELECT SQL_NO_CACHE count(*) as _total_count from ($tabla) as t $strWhere";

        //$strSql = "SELECT count(*) as total $strFrom $strWhere";
		$rows_result=query($strSql);
        
        //Profile tiempo
        //echo "<pre>$strSql</pre>";
        $now = microtime(true);
        $str_profile.= sprintf("Tardo en contar:  %f", round($now-$then)).'<br />';
        $start = microtime(true);

		$rows=mysql_fetch_object($rows_result);
		$rows=isset_or($rows->_total_count,0);
		
        if($reg_por_pagina==-1){
        	$reg_por_pagina=$rows;
        }
		//EL ULTIMO NUMERO DE LA PAGINA
		$ultima_pagina = ceil($rows/$reg_por_pagina);
		$pagina--;
		//SOLO LAS PAGINAS QUE EXISTAN
		if ($pagina < 0)
		{
			$pagina = 0;
		}
		
		$Limit=($pagina*$reg_por_pagina);	
		$pagina_actual=$pagina+1;
		$pagina_anterior=$pagina_actual-1;
		$pagina_siguente=$pagina_actual+1;
		if($excel==0){
			$tmp['pagina_inicio']='';
			if($pagina_actual>1){
				$tmp['pagina_inicio']="<a href='javascript:void(0);'  onclick=\"eval('$delirium_request').mostrar_pagina('0')\"><<</a>";
			}
			
			$tmp['pagina_anterior']='';
			if($pagina_anterior>=1){
				$tmp['pagina_anterior']="<a href='javascript:void(0);'  onclick=\"eval('$delirium_request').mostrar_pagina('$pagina_anterior')\"><</a>";
			}
			$tmp['pagina_lista_anteriores']=array();
			//for ($a=($pagina_anterior-2);$a<=$pagina_actual; ++$a){
            $a = ($pagina_anterior-2);while( $a <= $pagina_actual){
				if($a>1){
					$link=$a-1;					
					$tmp['pagina_lista_anteriores'][]['pagina']="<a href='javascript:void(0);'  onclick=\"eval('$delirium_request').mostrar_pagina('$link')\">$link</a>";
				}
                ++$a;
			}
			
			$tmp['pagina_actual']="<b><font face='verdana' size='2'>[ $pagina_actual ]</font> </b>  |";
			
			$tmp['pagina_lista_siguientes']=array();
			//for ($a=$pagina_siguente;$a<=($pagina_siguente+2); ++$a){
            $limite_pagina = $pagina_siguente+2;
            $a = $pagina_siguente;while( $a <= $limite_pagina){
				if($a<=$ultima_pagina){
					$tmp['pagina_lista_siguientes'][]['pagina']="<a href='javascript:void(0);'  onclick=\"eval('$delirium_request').mostrar_pagina('$a')\">$a</a>";
				}
                ++$a;
			}
			$tmp['pagina_siguiente']='';
			if($pagina_siguente<$ultima_pagina){
				$tmp['pagina_siguiente']="<a href='javascript:void(0);'  onclick=\"eval('$delirium_request').mostrar_pagina('$pagina_siguente')\">></a>";
			}
			$tmp['pagina_final']='';
			if($pagina_actual<$ultima_pagina){				
				$tmp['pagina_final']="<a href='javascript:void(0);'  onclick=\"eval('$delirium_request').mostrar_pagina('$ultima_pagina')\">>></a>";
			}
			
			$tmp['resumen_paginas']="
			<br/>
				<p>
					%PAGINA% $pagina_actual %OF% $ultima_pagina <br />
					%CANTIDAD_DE_REGISTROS% : $rows
				</p>
			
			";
			
			
		}
		
        $pos = strripos($tabla, 'ORDER BY ');
        if($pos === FALSE){
           $tabla = $tabla.$strOrdenarPor." LIMIT $Limit,$reg_por_pagina";
        }else{
            $regex = '#(Order By)(.*)(LIMIT|HAVING)?#ie';
            $tabla = preg_replace($regex,' ',$tabla);
            //$output = preg_replace($regex,"$2",$string);
            $tabla = str_ireplace("ORDER BY ", $strOrdenarPor." LIMIT $Limit,$reg_por_pagina  ", $tabla) ;
            //"OUT: ".echo $tabla."<br/>";
        }

		if($excel=='1'){
			$qry=$strSelect.$strCampos.$strFrom.$strWhere.$strOrdenarPor;
		}else{
            if($strWhere != ''){
                $qry=$strSelect.$strCampos.$strFrom.$strWhere.$strOrdenarPor." LIMIT $Limit,$reg_por_pagina ";
            }else{
                $qry=$strSelect.$strCampos." FROM ($tabla) as t ".$strWhere;
            }
		} 
		
        if( $DEBUG_QUERY == 1 ){
            echo $qry;
        }
        //echo_sql($qry);
        //exit();
		//$result=mysql_query($qry)or die(mysql_error()." qry:$qry");
        $result = query($qry);

        $now = microtime(true);
        $str_profile .= "<br/>".sprintf("Tardo en ejecutar la consulta:  %f", $now-$start);
        $start = microtime(true);
				
		$tmp['datos'].='<tbody>';
		if(mysql_num_rows($result)==0){
			$tmp['datos'].="<tr><td colspan='$col_span_titulo'>";			
			$tmp['datos']='%NO_SE_ENCONTRARON_REGISTROS%';
			$tmp['datos'].='</td></tr>';
		}else{			
			$i_css=0;
			while ($row=mysql_fetch_array($result)) {
				$cssClass=($i_css % 2 ==0)?'':'odd';
				$i_css++;
				$tmp['datos'].="<tr class=\"$cssClass\">";
				//for($i=0;$i<$total_campos; ++$i){
                $i = 0;while( $i < $total_campos){
                    $campo=explode('|',$array_campos[$i]);
					if(!in_array($i,$black_list)){
						$tmp['datos'].="<td style=\"text-align:$campo[7];\" >{$row[$i]}</td>";
					}
                    ++$i;
				}
				
				if($excel==0){
					if(trim($opciones!='')){
						$tmp['datos'].='<td >';
						$array_opciones=explode(',',$opciones);
						$opcion='';
						//for ($j=0;$j<sizeof($array_opciones); ++$j){
                        $total_opciones = sizeof($array_opciones);
                        $j = 0;while( $j < $total_opciones){
							$array_parametros=explode('|',$array_opciones[$j]);
                            if( $array_parametros[2] == ''){
                                $j++;
                                continue;
                            }
							$array_variables=explode('^',$array_parametros[2]);
                            $array_parametros[0]=addslashes($array_parametros[0]);

                            $css_campo_class = explode(' ', $array_parametros[5]);
                            $total_css_campo_class = sizeof($css_campo_class);
                            if (trim($array_parametros[5]) == ''){
                                $total_css_campo_class = 0;
                            }
                            $css_campo = '';
                            //for ( $z=0; $z < $total_css_campo_class; ++$z ){ 
                            $z = 0;while( $z < $total_css_campo_class){
                                $css_campo .= ' '.isset_or($row[$css_campo_class[$z]], '');
                                ++$z;
                            }

                            //$css_campo = ($array_parametros[5] == '') ? '' : $row[$array_parametros[5]];

							$opcion.="<span><input class='btn {$array_parametros[4]} $css_campo' type='button' value='{$array_parametros[0]}' ";
							//$opcion.="<span><input class='btn {$array_parametros[4]} $css_campo' type='button' value='{$array_parametros[0]} {$row[$array_parametros[5]]}' ";
							
							switch ($array_parametros[3]){
								case 'link':
									$link=$array_parametros[1].'&';
									$total_variables=sizeof($array_variables)-1;
									//for($x=0;$x<$total_variables; ++$x){
                                    $x = 0;while($x < $total_variables){
										$variable=explode('*',$array_variables[$x]);
										switch ($variable[0]){
											case 'texto':
												$link.=$variable[1].'='.$variable[2];
												break;
											case 'campo':												
												$link.=$variable[1].'='.$row[$variable[2]];
												break;
										}
										if($x<$total_variables){
											$link.='&';
										}
                                        ++$x;
									}
									$opcion.=" onclick=\"window.location='index.asp?proc=$link'\" >";
									break;
								case 'onclick':
									$function=$array_parametros[1];
									$total_variables=sizeof($array_variables);
									$total_variables--;
									$parametro='';
									//for($x=0;$x<$total_variables; ++$x){
                                    $x = 0;while($x < $total_variables){
										$variable=explode('*',$array_variables[$x]);
										switch ($variable[0]){
											case 'texto':
                                                $variable[2]=addslashes($variable[2]);
												$parametro.="'{$variable[2]}'";
												break;
											case 'campo':
                                                $row[$variable[2]]=addslashes($row[$variable[2]]);
                                                $row[$variable[2]]=htmlspecialchars(($row[$variable[2]]));
												$parametro.="'{$row[$variable[2]]}'";
												break;
										}
										if(($x+1)<$total_variables){
											$parametro.=',';
										}
                                        ++$x;
									}
									$opcion.=" onclick=\"$function($parametro)\" ></span>";
									break;
							}
                            ++$j;
						}						
						$tmp['datos'].=$opcion;
						$tmp['datos'].='</td>';
					}
				}
				$tmp['datos'].='</tr>';										
			}
		}		
		$tmp['datos'].='</tbody>';
		$tmp['titulo']=$titulo;		
		$tmp['col_span_titulo']=$col_span_titulo;		
		if($excel==0){
			$tmp['col_span_titulo']++;
		}
		$tmp['col_span_titulo']=number_format($tmp['col_span_titulo'],0);
					
		$base=stripslashes($base);
		$tmp['panel_busqueda']="
		<script>
			window.onload=function(){
				$delirium_request.mostrar_ocultar();
			}
		</script>
		<table border=0 width='95%' >
			<tr>
				<td colspan=6 align='left' id='".$delirium_request."_mostrar_ocultar'>
					<a href='javascript:void(0);' onclick=\"eval('$delirium_request').mostrar_ocultar(this)\"; >%MOSTRAR_OCULTAR_CUADRO_BUSQUEDA%</a>
				</td>
			</tr>
		</table>
		<br>
		<div id='div_".$delirium_request."_busqueda_contenedor' style=\"width:95%;display:'';\">
		$base
		</div><br>		
		";

        $now = microtime(true);
        $str_profile .= "<br/>" . sprintf("Tardo en ejecutar el archivo completo:  %f", round($now-$then));
		
        if ($profile == 1){
            $tmp['panel_busqueda'] = $str_profile. $tmp['panel_busqueda'];
        }

        mysql_free_result($rows_result);
        mysql_free_result($result);
        unset($rows);
		return $tmp;
	}
	
	function searchweb($datos){
		extract($datos);		
		
		$pagina=(isset($_REQUEST['p']))?$_REQUEST['p']:0;
		$array_campos=$datos['fields'];
		$total_campos=sizeof($array_campos);
		$reg_por_pagina=(isset($num_rows))?$num_rows:20;
		$strSelect="Select ";
		$strCampos="";
		$strFrom=" From ($from) as t ";
		$strWhere=" Where ";
		$strOrdenarPor = " Order By $orderby";		
		$black_list=array();
		$tmp=array();
		$tmp['datos']="<tr>";
		$col_span_titulo=0;
		for($i=0;$i<$total_campos;$i++){
			$campo=$array_campos[$i];
			if($i>0){
				$strCampos.=",";
			}
			if($campo['datatype']=="moneda"){
				$strCampos.="CONCAT('$', TRIM(LEADING '0' FROM FORMAT({$campo[1]}, 3))) as {$campo['field']}";
			}else{
				$strCampos.="UPPER({$campo['field']}) as {$campo['field']}";
			}
					
			
			if($campo['show']==1){
				$tmp['datos'].="<td align=\"Center\" class=\"delirium_search_header\" width=\"10%\" height=\"30\">";
				
				$tmp['datos'].="<a href=\"javascript:void(0);\">
									{$campo['title']}
								</a>";
				
				$tmp['datos'].="</td>";
				$col_span_titulo++;
			}else{
				$black_list[]=$i;
			}
			
			if ($campo['search']==1 && isset($_REQUEST['s'])) {
				if ($strWhere!=" Where ") {
					$strWhere.=" OR ";
				}
				$strWhere.="{$campo['field']} LIKE '%{$_REQUEST['s']}%' ";
			}
		}
		if ($strWhere==" Where ") {
			$strWhere=" ";
		}

		$tmp['datos'].="<td align=\"Center\" width=\"10%\" height=\"30\" class=\"delirium_search_header\" >%OPCIONES%</td>";
		$tmp['datos'].="</tr>";						
		
		
		if (!isset($pagina)) {
			$pagina=0;
		}
		$rows_result=mysql_query("SELECT count(*) as total $strFrom $strWhere") or die(mysql_error()."qry: SELECT count(*) as total $strFrom $strWhere");
		$rows=mysql_fetch_object($rows_result);
		$rows=$rows->total;
		
		//EL ULTIMO NUMERO DE LA PAGINA
		$ultima_pagina = ceil($rows/$reg_por_pagina);
		$pagina--;
		//SOLO LAS PAGINAS QUE EXISTAN
		if ($pagina < 0)
		{
			$pagina = 0;
		}
		
		$Limit=($pagina*$reg_por_pagina);	
		$pagina_actual=$pagina+1;
		$pagina_anterior=$pagina_actual-1;
		$pagina_siguente=$pagina_actual+1;
		
		$sq="";
		if(isset($_REQUEST['s'])){
			if(trim($_REQUEST['s'])!=""){
				$sq="&s={$_REQUEST['s']}";
			}
		}
		
		$tmp['pagina_inicio']="";
		if($pagina_actual>1){
			$tmp['pagina_inicio']="<a href='index.php?do={$_REQUEST['do']}&p=0$sq' ><<</a>";
		}
		
		$tmp['pagina_anterior']="";
		if($pagina_anterior>=1){
			$tmp['pagina_anterior']="<a href='index.php?p=$pagina_anterior&do={$_REQUEST['do']}$sq'><</a>";
		}
		$tmp['pagina_lista_anteriores']=array();
		for ($a=($pagina_anterior-2);$a<=$pagina_actual;$a++){
			if($a>1){
				$link=$a-1;					
				$tmp['pagina_lista_anteriores'][]['pagina']="<a href='index.php?p=$link&do={$_REQUEST['do']}$sq'>$link</a>";
			}
		}
		
		$tmp['pagina_actual']="<b><font face='verdana' size='2'>[ $pagina_actual ]</font> </b>  |";
		
		$tmp['pagina_lista_siguientes']=array();
		for ($a=$pagina_siguente;$a<=($pagina_siguente+2);$a++){
			if($a<=$ultima_pagina){
				$tmp['pagina_lista_siguientes'][]['pagina']="<a href='index.php?p=$a&do={$_REQUEST['do']}$sq'>$a</a>";
			}
		}
		$tmp['pagina_siguiente']="";
		if($pagina_siguente<$ultima_pagina){
			$tmp['pagina_siguiente']="<a href='index.php?p=$pagina_siguente&do={$_REQUEST['do']}$sq'>></a>";
		}
		$tmp['pagina_final']="";
		if($pagina_actual<$ultima_pagina){				
			$tmp['pagina_final']="<a href='index.php?p=$ultima_pagina&do={$_REQUEST['do']}$sq' >>></a>";
		}
		
		if(isset($_REQUEST['s'])){
			$tmp['resumen_paginas']="
			<br/>
				<p>
					Página $pagina_actual de $ultima_pagina <br />
					Cantidad de resultados para <b>{$_REQUEST['s']}</b> : $rows
				</p>
			
			";
		}else{
			$tmp['resumen_paginas']="
			<br/>
				<p>
					Página $pagina_actual de $ultima_pagina <br />
					Cantidad de registros : $rows
				</p>			
			";
		}
						
		$qry=$strSelect.$strCampos.$strFrom.$strWhere.$strOrdenarPor." LIMIT $Limit,$reg_por_pagina ";		 
		
		$result=mysql_query($qry)or die(mysql_error()." qry:$qry");
				
		if(mysql_num_rows($result)==0){
			$tmp['datos']="No se encontraron registros";
		}else{			
			$i_css=0;
			while ($row=mysql_fetch_array($result)) {
				$cssClass=($i_css % 2 ==0)?"delirium_search_cell":"delirium_search_cell2";
				$i_css++;
				$tmp['datos'].="<tr>";
				for($i=0;$i<$total_campos;$i++){
					if(!in_array($i,$black_list)){
						$tmp['datos'].="<td valign=\"top\" class=\"$cssClass\">{$row[$i]}</td>";
					}
				}
				$array_opciones=$options;
					if(sizeof($array_opciones)>0){
						$tmp['datos'].="<td class=\"$cssClass\" nowrap=\"nowrap\" align=\"center\">";						
						$opcion="";
						for ($j=0;$j<sizeof($array_opciones);$j++){
							$opcion.="<input type='button' value='{$array_opciones[$j]['title']}' ";
							$array_parametros=$array_opciones[$j]['param'];
							switch ($array_opciones[$j]['type']){
								case "link":
									$link=$array_opciones[$j]['exec']."&";
									$total_variables=sizeof($array_opciones[$j]['param']);
									for($x=0;$x<$total_variables;$x++){
										$variable=$array_opciones[$j]['param'][$x];
										switch ($variable['type']){
											case "text":
												$link.=$variable['variable']."=".$variable['data'];
												break;
											case "field":												
												$link.=$variable['variable']."=".$row[$variable['data']];
												break;
										}
										if($x<$total_variables){
											$link.="&";
										}
									}
									$opcion.=" onclick=\"window.location='$link'\" >";
									break;
								case "onclick":									
									$function=$array_opciones[$j]['exec'];
									$total_variables=sizeof($array_opciones[$j]['param']);
									$parametro="";
									for($x=0;$x<$total_variables;$x++){
										$variable=$array_opciones[$j]['param'][$x];
										switch ($variable['type']){
											case "text":
												$parametro.="'{$variable['data']}'";
												break;
											case "field":
												$parametro.="'{$row[$variable['data']]}'";
												break;
										}
										if(($x+1)<$total_variables){
											$parametro.=",";
										}
									}
									$opcion.=" onclick=\"$function($parametro)\" >";
									break;
							}
						}						
						$tmp['datos'].="$opcion";
						$tmp['datos'].="</td>";
					}
				}
				$tmp['datos'].="</tr>";										
			}
	
		$tmp['titulo']=$title;		
		$tmp['col_span_titulo']=$col_span_titulo;		
		
		$tmp['col_span_titulo']++;
		
		$tmp['col_span_titulo']=number_format($tmp['col_span_titulo'],0);
					
		
		$tmp['panel_busqueda']="
		<form action='index.php?do={$_REQUEST['do']}' method='POST'>		
		<table border=0 width='95%' align='center'>
			<tr>
				<td align='center'>
					<input type='text' id='s' name='s' /><input type='submit' value='%BUSCAR%'>
				</td>
			</tr>
		</table>
		<form>		
		";

        mysql_free_result($rows);
        mysql_free_result($result);
		
		return $tmp;
	}
}

}
?>
