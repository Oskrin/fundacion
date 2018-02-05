<?php 
	if(!isset($_SESSION)){
        session_start();        
    }
	include_once('../../../../admin/class.php');
	include_once('../../../../admin/funciones_generales.php');
	$class = new constante();	
	$fecha = $class->fecha();	

	if ($_POST['tipo'] == "cargarEmpresa") {				
		$lista = array();
		$sql = "SELECT S.id, E.razonSocial, E.ruc, S.puntoEmision, S.establecimiento, S.ciudad from empresa E inner join sucursal S on E.id = S.idEmpresa where S.enLote = '' order by S.id asc";
		$sql = $class->consulta($sql);							
		while ($row = $class->fetch_array($sql)) {
			$lista[] = array('id' => $row[0], 'nombre' => ($row[1] .' - ' .$row[5]), 'emision' => $row[3] , 'establecimiento' => $row[4] );

		}
		print_r(json_encode($lista));
	}
	if ($_POST['tipo'] == "cargarTipo") {				
		$lista = array();
		$sql = "SELECT id,codigo, nombre FROM tipoComprobante order by id asc";
		$sql = $class->consulta($sql);							
		while ($row = $class->fetch_array($sql)) {
			$lista[] = array('id' => $row[0], 'nombre' => ($row[1] .' - ' .$row[2]));

		}
		print_r(json_encode($lista));
	}
	if ($_POST['tipo'] == "cargarFormaPago") {				
		$lista = array();
		$sql = "SELECT id,codigo, nombre FROM formaPago WHERE estado = 'Activo' order by id asc";
		$sql = $class->consulta($sql);								
		while ($row = $class->fetch_array($sql)) {
			$lista[] = array('id' => $row[0], 'nombre' => ($row[1] .' - ' .$row[2]));

		}
		print_r(json_encode($lista));
	}
	if ($_POST['tipo'] == "cargarCodigoRetencion") {				
		$lista = array();
		$sql = "SELECT id,codigo, nombre FROM tipoRetencion order by id asc";
		$sql = $class->consulta($sql);										
		while ($row = $class->fetch_array($sql)) {
			$lista[] = array('id' => $row[0], 'nombre' => ($row[1] .' - ' .$row[2]));
		}
		print_r(json_encode($lista));
	}
	if ($_POST['tipo'] == "cargarImpuesto") {				
		$lista = array();
		$sql = "SELECT id,codigo,nombre,descripcion FROM tarifaRetencion WHERE idTarifa = '".$_POST['id']."'";
		$sql = $class->consulta($sql);											
		while ($row = $class->fetch_array($sql)) {
			$lista[] = array('id' => $row[0], 'nombre' => ($row[1] .' - ' .$row[3]), 'porcentaje' => $row[2]);

		}
		print_r(json_encode($lista));
	}

	if ($_POST['tipo'] == "Generar Datos") {
		include 'generarXML.php';	
		include '../../firma/firma.php';	
		include '../../firma/xades.php';
		include_once('../../../../admin/correolocal.php');	
		/*print_r($_POST['vect1']);//tipo comprobante
		print_r($_POST['vect2']);///tarifa retencion
		print_r($_POST['vect3']);//base imponible
		print_r($_POST['vect4']);//tipo retencion
		print_r($_POST['vect5']);//porcentaje
		print_r($_POST['vect6']);//valor retenido*/
		$vect1 = array();
		$vect2 = array();
		$vect3 = array();
		$vect4 = array();
		$vect5 = array();
		$vect6 = array();
		$vect1 = $_POST['vect1'];
		$vect2 = $_POST['vect2'];
		$vect3 = $_POST['vect3'];
		$vect4 = $_POST['vect4'];
		$vect5 = $_POST['vect5'];
		$vect6 = $_POST['vect6'];
		$totalComprobante = 0;
		for ($i = 0; $i < count($vect6); $i++) { 
			$totalComprobante = $totalComprobante + $vect6[$i];
		}
		$defaultMail = mailDefecto;	
	    $codDoc = '07';///tipo documento
	    $sql = "select codigo from tipoambiente where estado = 'Activo' order by codigo asc";
	    $sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {
			$ambiente = $row[0];	
		}
		$sql = "select codigo from tipoemision order by codigo asc  limit 1";
	    $sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {
			$emision = $row[0];	//normal cuando generamos la clave
		}

		$cont = 0;
		$secuencialComprobante = 0;
		////datos del contribuyente///
		$sql = "select id from contribuyente where idTipoIdentificacion = '".$_POST['idIdentificacion']."' and identificacion = '".$_POST['identificacion']."'";		
		$sql = $class->consulta($sql);
		while ($row = $class->fetch_array($sql)) {
			$cont = $row[0];
		}
		if($cont == 0){////si no existe ingreso uno nuevo
			$id_contribuyente = $class->idz();
			$temp = $id_contribuyente;			
			$sql = "insert into contribuyente VALUES ('".$id_contribuyente."','".$_POST['idIdentificacion']."','".$_POST['identificacion']."','".$_POST["correo"]."','".$_POST["razonSocial"]."','".$_POST["nombreComercial"]."','".$_POST["direccion"]."','".$_POST["telefono"]."','".$_POST['obligacion']."','1','0')";			
			if($class->consulta($sql)){
				$data = 1;	//DATOS AGREGADOS
			}else{
				$data = 4;	//ERROR EN LA BASE
			}	
		}else{
			$sql = "update contribuyente set idTipoIdentificacion='".$_POST['idIdentificacion']."',identificacion='".$_POST['identificacion']."',email='".$_POST["correo"]."',razonSocial='".$_POST["razonSocial"]."',nombreComercial='".$_POST["nombreComercial"]."',direccion='".$_POST["direccion"]."',telefono='".$_POST['telefono']."',obligado='".$_POST['obligacion']."' where id='".$cont."'";
			$temp = $cont;
			if($class->consulta($sql)){
				$data = 1;	//DATOS AGREGADOS
			}else{
				$data = 4;	//ERROR EN LA BASE
			}
		}	
		if($data == 1){////GENERACION DEL COMPROBANTE
			$id = $class->idz();				
			$sql = "select E.ruc,E.clave, E.token, S.establecimiento,S.puntoEmision from empresa E inner join sucursal S on S.idEmpresa = E.id where S.id  = '".$_POST['idEmpresa']."'";
			$sql = $class->consulta($sql);
			while ($row = $class->fetch_array($sql)) {
				$ruc = $row[0];
				$pass = $row[1];
				$token = $row[2];
				$establecimiento = $row[3];
				$puntoEmision = $row[4];
			}	
			$sql = "select  nroRetencion from nroDocumento where idSucursal = '".$_POST['idEmpresa']."'";
			$sql = $class->consulta($sql);
			while ($row = $class->fetch_array($sql)) {
				$secuencialComprobante = $row[0];
			}	
			$secuencialComprobante = $secuencialComprobante + 1;						
			$clave = $class->generarClave($_POST['fechaEmision'],$codDoc,$ruc,$ambiente,$establecimiento.''.$puntoEmision,$secuencialComprobante,$_POST['fechaEmision'],$emision);	
			$sql = "insert into comprobanteRetencion VALUES('".$id."','".$temp."','".$_POST['fechaEmision']."','','".$_POST['idTipoComprobante']."','".$_POST['numeroDocumento']."','9','','".$clave."','".$establecimiento."','".$puntoEmision."','".$_POST['mes'] .'/'. $_POST['anio']."','".$secuencialComprobante."','1','".$_POST['idEmpresa']."','','".$emision."','".$_SESSION['userAccion']['id']."','".$fecha."','".$totalComprobante."','','')";				
			if($class->consulta($sql)){////cabecera generada
				$sql = "UPDATE nroDocumento SET nroRetencion = '".$secuencialComprobante."' where idSucursal = '".$_POST['idEmpresa']."'";					
				if($class->consulta($sql)){///modifico numero documento
					for ($i = 0; $i < count($vect1); $i++) { 
						$id_detalles = $class->idz();
						$sql = "insert into detalleComprobanteRetencion VALUES('".$id_detalles."','".$id."','".$vect2[$i]."','".$vect3[$i]."','".$vect4[$i]."','".$vect5[$i]."','".$vect6[$i]."')";	
						
						if($class->consulta($sql)){
							$data = 1;	//DETALLE GENERADO
						}else{
							$data = 4;	//ERROR EN LA BASE
						}
					}
					if($data == 1){////GENERACION DEL ARCHIVO Y VALIDACION EN EL WEBSERVICE SRI
						$result = generarXML($id,$codDoc,$ambiente,$emision);
						$resp = generarFirma($result, $clave, 'comprobanteRetencion',$pass,$token,$ambiente);//devuelvo archivo firmado en formato XADESBES
						if($resp == 5){
							$data = 5;//ARCHIVO NO EXISTE
						}else{
							if($resp == 6){
								$data = 6;////CONTRASEÑA DE TOKEN INCORRECTA
							}else{								
								$respWeb = webService($resp,$ambiente,$clave,'','comprobanteRetencion',$pass,$token,'0');////ENVIO EL ARCHIVO XML PARA VALIDAR
								if(isset($respWeb['RespuestaRecepcionComprobante']['estado']) == 'RECIBIDA'){
									$respuesta = consultarComprobante($ambiente, $clave);									
									if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO'){										
					            	   	if($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado == 'AUTORIZADO'){
						            	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
						        	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
						    	            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
							                $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$id."'";	
							                $class->consulta($sql);
							                $dataFile = generarXMLCDATA($respuesta);		                
							                $doc = new DOMDocument('1.0', 'UTF-8');
						        			$doc->loadXML($dataFile);//xml	 
						        			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')){
						        				/*include '../generarPDF.php';							        				
							        			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
							        			$email = $_POST['correo'];
							        			if(trim($email) == ''){		
													$sql = "update comprobanteretencion set estado = '1' where id = '".$id."'";			
													if($class->consulta($sql)){
														$data = 1;///datos actualizados
													}else{
														$data = 4;//error al momento de guadar
													}
												}else{
													$data = 3; ///error al momento de enviar el correo
													$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$id."'";
								    				$class->consulta($sql);	
												}	
						        			}else{
						        				$data = 2;
						        				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$id."'";////ERROR AL GENERAR LOS DOCUMENTOS
							                	$class->consulta($sql);			                	
						        			}
					        			}else{
					        				$data =	7;
											$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$id."'";////no autorizado	
				                			$class->consulta($sql);
					        			}      
									}else{
										$data =	$respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
										$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$id."'";////rechazado	
			                			$class->consulta($sql);	
									}
								}else{
									$data = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje'];
									$sql = "UPDATE comprobanteRetencion SET estado = '8' WHERE id = '".$id."'";	//ERROR EN EL WEB SERVICE
					    			$class->consulta($sql);
								}
							}
						}	
					}
				}else{
					$data = 4;////ERROR EN LA BASE
				}	
			}else{
				$data = 4; ////ERROR EN LA BASE
			}
		}
		$lista[] = array('estado' => $data, 'id' => $id);
		echo json_encode($lista);
	}	
?>