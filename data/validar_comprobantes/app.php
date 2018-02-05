<?php
    include_once('../../../../admin/class.php');		 
    include_once('../../../../admin/correolocal.php');		    
	if(!isset($_SESSION)){
        session_start();                
    }
   // error_reporting(0);
    $class = new constante();	
	$fecha = $class->fecha();	
	$fecha2 = $class->fecha2();	
	$defaulMail = mailDefecto;
	$data = array();
	if (isset($_POST['reenviarCorreo']) == "reenviarCorreo") {
		$sql = "SELECT C.email,CR.totalComprobante,C.nombreComercial FROM comprobanteretencion CR inner join contribuyente C on CR.idContribuyente = C.id where CR.id = '".$_POST['id']."'";				
		$sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {			
			$email = $row[0];
			$total = $row[1];
			$nombre = $row[2];
		} 
		
		if(trim($email) == '') {		
			$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
			if($class->consulta($sql)) {
				$data = 1;///datos actualizados
			} else {
				$data = 4;//error al momento de guadar
			}
		} else {
			include 'generarPDF.php';			        				      
			$data = correo($fecha,$total,$_POST['aut'].'.xml',$_POST['aut'].'.pdf',$nombre,$email,'../comprobantes/'.$_POST['aut'].'.xml',generarPDF($_POST['id']),1);	

			if($data == 1) {
				$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";
				if($class->consulta($sql)) {
					$data = 1; ///datos actualizados
				} else {
					$data = 4; //error al momento de guadar
				}
			}	
		}
		
		echo $data;
	}
	if (isset($_POST['generarArchivos']) == "generarArchivos") {
		include '../comprobanteretencion/generarXML.php';
		include '../../firma/firma.php';				
		$codDoc = '07';///tipo documento
		$sql = "select CR.ambiente,CR.emision,CR.fechaEmision, E.ruc,CR.puntoemision, CR.establecimiento, CR.secuencial,E.clave, E.token, C.email, C.nombreComercial, CR.totalComprobante, CR.numeroComprobante, CR.claveAcceso from comprobanteretencion CR inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id inner join contribuyente C on CR.idContribuyente = C.id WHERE CR.id = '".$_POST['id']."'";
		$sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {			
			$ambiente = $row[0];
			$emision = $row[1];
			$fechaEmision = $row[2];
			$ruc = $row[3];
			$puntoEmision = $row[4];
			$establecimiento = $row[5];
			$secuencial = $row[6];
			$pass = $row[7];
			$token = $row[8];
			$email = $row[9];
			$nombre = $row[10];
			$totalComprobante = $row[11];
			$numeroComprobante = $row[12];
			$clave = $row[13];				
		}			
		$respuesta = consultarComprobante($ambiente, $clave);									
		if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO') {
			//$estado = 3; //autorizado actuzalizar los campos faltantes en el comprobante
    	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
            $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$_POST['id']."'";	
            $class->consulta($sql);
            $dataFile = generarXMLCDATA($respuesta);		                
            $doc = new DOMDocument('1.0', 'UTF-8');
			$doc->loadXML($dataFile);//xml	 
			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')) {
				/*include '../generarPDF.php';					
    			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
    			if(trim($email) == '') {		
					$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
					if($class->consulta($sql)) {
						$data = 1;///datos actualizados
					} else {
						$data = 4;//error al momento de guadar
					}
				} else {
					$data = 3; ///error al momento de enviar el correo
					$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";
    				$class->consulta($sql);	
				}
			} else {
				$data = 2;
				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";////ERROR AL GENERAR LOS DOCUMENTOS
            	$class->consulta($sql);			                	
			}      
		} else {
			$data =	$respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
			$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$_POST['id']."'";////rechazado	
			$class->consulta($sql);	
		}
		echo $data;
	}

	if (isset($_POST['errorWebService']) == "errorWebService") {		
		include '../comprobanteretencion/generarXML.php';		
		include '../../firma/firma.php';	
		include '../../firma/xades.php';
		$codDoc = '07';///tipo documento
		$sql = "select CR.ambiente,CR.emision,CR.fechaEmision, E.ruc,CR.puntoemision, CR.establecimiento, CR.secuencial,E.clave, E.token, C.email, C.nombreComercial, CR.totalComprobante, CR.numeroComprobante, CR.claveAcceso from comprobanteretencion CR inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id inner join contribuyente C on CR.idContribuyente = C.id WHERE CR.id = '".$_POST['id']."'";
		$sql = $class->consulta($sql);
		while ($row = $class->fetch_array($sql)) {
			$emision = $row[1];
			$fechaEmision = $row[2];
			$ruc = $row[3];
			$puntoEmision = $row[4];
			$establecimiento = $row[5];
			$secuencial = $row[6];
			$pass = $row[7];
			$token = $row[8];
			$email = $row[9];
			$nombre = $row[10];
			$totalComprobante = $row[11];
			$numeroComprobante = $row[12];
			$clave = $row[13];	
		}
		$sql = "select codigo from tipoambiente where estado = 'Activo' order by codigo asc";	    
	    $sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {
			$ambiente = $row[0];
		}  		
		$result = generarXML($_POST['id'],$codDoc,$ambiente,$emision);										
		$resp = generarFirma($result, $clave, 'comprobanteRetencion',$pass,$token,$ambiente,'1');		
		if($resp == 5){
			$data = 5;//ARCHIVO NO EXISTE
		} else {
			if($resp == 6) {
				$data = 6;////CONTRASEÑA DE TOKEN INCORRECTA
			} else {												
				$respuesta = consultarComprobante($ambiente, $clave);				
				if($respuesta->RespuestaAutorizacionComprobante->numeroComprobantes == 0) {					
					$respWeb = webService($resp,$ambiente,$clave,'','comprobanteRetencion',$pass,$token,'0');						
					if(isset($respWeb['RespuestaRecepcionComprobante']['estado']) == 'RECIBIDA') {
						$respuesta = consultarComprobante($ambiente, $clave);									
						if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO') {
							//$estado = 3;//autorizado actuzalizar los campos faltantes en el comprobante
		            	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
		        	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
		    	            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
			                $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$_POST['id']."'";	
			                $class->consulta($sql);
			                $dataFile = generarXMLCDATA($respuesta);		                
			                $doc = new DOMDocument('1.0', 'UTF-8');
		        			$doc->loadXML($dataFile);//xml	 
		        			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')) {
		        				/*include '../generarPDF.php';			        				
			        			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
			        			if(trim($email) == '') {		
									$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
									if($class->consulta($sql)) {
										$data = 1;///datos actualizados
									} else {
										$data = 4;//error al momento de guadar
									}
								} else {
									$data = 3; ///error al momento de enviar el correo
									$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";
				    				$class->consulta($sql);	
								}			        			
		        			} else {
		        				$data = 2;
		        				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";////ERROR AL GENERAR LOS DOCUMENTOS
			                	$class->consulta($sql);			                	
		        			}      
						} else {
							$data =	$respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
							$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$_POST['id']."'";////rechazado	
	            			$class->consulta($sql);	
						}
					} else {
						$data = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje'];
						$sql = "UPDATE comprobanteRetencion SET estado = '8' WHERE id = '".$_POST['id']."'";	//ERROR EN EL WEB SERVICE
		    			$class->consulta($sql);
					}
				} else {
					if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO') {
						//$estado = 3;//autorizado actuzalizar los campos faltantes en el comprobante
	            	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
	        	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
	    	            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
		                $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$_POST['id']."'";	
		                $class->consulta($sql);
		                $dataFile = generarXMLCDATA($respuesta);		                
		                $doc = new DOMDocument('1.0', 'UTF-8');
	        			$doc->loadXML($dataFile);//xml	 
	        			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')){
	        				/*include '../generarPDF.php';		        				
		        			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
							if(trim($email) == '') {		
								$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
								if($class->consulta($sql)) {
									$data = 1;///datos actualizados
								} else {
									$data = 4;//error al momento de guadar
								}
							} else {
								$data = 3; ///error al momento de enviar el correo
								$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";
			    				$class->consulta($sql);	
							}
	        			} else {
	        				$data = 2;
	        				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";////ERROR AL GENERAR LOS DOCUMENTOS
		                	$class->consulta($sql);			                	
	        			}      
					} else {
						$data =	$respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
						$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$_POST['id']."'";////rechazado	
	        			$class->consulta($sql);	
					}
				}				
			}
		}	
		echo $data;
	}

	if (isset($_POST['volverValidar']) == "volverValidar") {		
		include '../comprobanteretencion/generarXML.php';		
		include '../firma/firma.php';	
		include '../firma/xades.php';
		$codDoc = '07';///tipo documento
		$sql = "select CR.ambiente,CR.emision,CR.fechaEmision, E.ruc,CR.puntoemision, CR.establecimiento, CR.secuencial,E.clave, E.token, C.email, C.nombreComercial, CR.totalComprobante, CR.numeroComprobante, CR.claveAcceso from comprobanteretencion CR inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id inner join contribuyente C on CR.idContribuyente = C.id WHERE CR.id = '".$_POST['id']."'";
		$sql = $class->consulta($sql);
		while ($row = $class->fetch_array($sql)) {
			$emision = $row[1];
			$fechaEmision = $row[2];
			$ruc = $row[3];
			$puntoEmision = $row[4];
			$establecimiento = $row[5];
			$secuencial = $row[6];
			$pass = $row[7];
			$token = $row[8];
			$email = $row[9];
			$nombre = $row[10];
			$totalComprobante = $row[11];
			$numeroComprobante = $row[12];
			$clave = $row[13];	
		}
		$sql = "select codigo from tipoambiente where estado = 'Activo' order by codigo asc";	    
	    $sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {
			$ambiente = $row[0];
		} 
																
		$respuesta = consultarComprobante($ambiente, $clave);
		if($respuesta->RespuestaAutorizacionComprobante->numeroComprobantes == 0) {					
			$respWeb = webService($resp,$ambiente,$clave,'','comprobanteRetencion',$pass,$token,'0');											
			if(isset($respWeb['RespuestaRecepcionComprobante']['estado']) == 'RECIBIDA') {
				$respuesta = consultarComprobante($ambiente, $clave);									
				if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO'){
					//$estado = 3;//autorizado actuzalizar los campos faltantes en el comprobante
            	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
        	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
    	            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
	                $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$_POST['id']."'";	
	                $class->consulta($sql);
	                $dataFile = generarXMLCDATA($respuesta);		                
	                $doc = new DOMDocument('1.0', 'UTF-8');
        			$doc->loadXML($dataFile);//xml	 
        			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')) {
        				/*include '../generarPDF.php';			        				
	        			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
	        			if(trim($email) == '') {		
							$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
							if($class->consulta($sql)){
								$data = 1;///datos actualizados
							} else {
								$data = 4;//error al momento de guadar
							}
						} else {
							$data = 3; ///error al momento de enviar el correo
							$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";
		    				$class->consulta($sql);	
						}			        			
        			} else {
        				$data = 2;
        				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";////ERROR AL GENERAR LOS DOCUMENTOS
	                	$class->consulta($sql);			                	
        			}      
				} else {
					$data =	$respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
					$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$_POST['id']."'";////rechazado	
        			$class->consulta($sql);	
				}
			} else {
				$data = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje'];
				$sql = "UPDATE comprobanteRetencion SET estado = '8' WHERE id = '".$_POST['id']."'";	//ERROR EN EL WEB SERVICE
    			$class->consulta($sql);
			}
		} else {
			if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO'){
				//$estado = 3;//autorizado actuzalizar los campos faltantes en el comprobante
        	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
    	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
	            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
                $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$_POST['id']."'";	
                $class->consulta($sql);
                $dataFile = generarXMLCDATA($respuesta);		                
                $doc = new DOMDocument('1.0', 'UTF-8');
    			$doc->loadXML($dataFile);//xml	 
    			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')) {
    				/*include '../generarPDF.php';		        				
        			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
					if(trim($email) == '') {		
						$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
						if($class->consulta($sql)) {
							$data = 1;///datos actualizados
						} else {
							$data = 4;//error al momento de guadar
						}
					} else {
						$data = 3; ///error al momento de enviar el correo
						$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";
	    				$class->consulta($sql);	
					}
    			} else {
    				$data = 2;
    				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";////ERROR AL GENERAR LOS DOCUMENTOS
                	$class->consulta($sql);			                	
    			}      
			} else {
				$data =	$respuesta['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'];
				$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$_POST['id']."'";////rechazado	
    			$class->consulta($sql);	
			}
		}	
		echo $data;
	}
	if (isset($_POST['generarFirma']) == "generarFirma") {		
		include '../comprobanteRetencion/generarXML.php';		
		include '../firma/firma.php';	
		include '../firma/xades.php';
		$codDoc = '07';///tipo documento
	    $sql = "select CR.ambiente,CR.emision,CR.fechaEmision, E.ruc,CR.puntoemision, CR.establecimiento, CR.secuencial,E.clave, E.token, C.email, C.nombreComercial, CR.totalComprobante, CR.numeroComprobante, CR.claveAcceso from comprobanteretencion CR inner join sucursal S on S.id = CR.idSucursal inner join empresa E on S.idEmpresa = E.id inner join contribuyente C on CR.idContribuyente = C.id WHERE CR.id = '".$_POST['id']."'";
	    $sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {
			//$ambiente = $row[0];	
			$emision = $row[1];
			$fechaEmision = $row[2];
			$ruc = $row[3];
			$puntoEmision = $row[4];
			$establecimiento = $row[5];
			$secuencial = $row[6];
			$pass = $row[7];
			$token = $row[8];
			$email = $row[9];
			$nombre = $row[10];
			$totalComprobante = $row[11];
			$numeroComprobante = $row[12];
			$clave = $row[13];
		}
		$sql = "select codigo from tipoambiente where estado = 'Activo' order by codigo asc";
	    $sql = $class->consulta($sql);						
		while ($row = $class->fetch_array($sql)) {
			$ambiente = $row[0];	
		}  	

		$result = generarXML($_POST['id'],$codDoc,$ambiente,$emision);				
		$resp = generarFirma($result, $clave, 'comprobanteRetencion',$pass,$token,$ambiente,'1');			
		if($resp == 5) {
			$data = 5;//ARCHIVO NO EXISTE
		} else {
			if($resp == 6) {
				$data = 6;////CONTRASEÑA DE TOKEN INCORRECTA
			} else {								
				$respWeb = webService($resp,$ambiente,$clave,'','comprobanteRetencion',$pass,$token,'0');////ENVIO EL ARCHIVO XML PARA VALIDAR
				
				if(isset($respWeb['RespuestaRecepcionComprobante']['estado']) == 'RECIBIDA'){
					$respuesta = consultarComprobante($ambiente, $clave);														
					if(isset($respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado) == 'AUTORIZADO'){
						//$estado = 3;//autorizado actuzalizar los campos faltantes en el comprobante
	            	    $numeroAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion;
	        	        $fechaAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion;
	    	            $ambienteAutorizacion = $respuesta->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->ambiente;
		                $sql = "UPDATE comprobanteRetencion SET fechaAutorizacion = '".$fechaAutorizacion."', estado = '2', numeroAutorizacion = '".$numeroAutorizacion."', ambiente = '".$ambienteAutorizacion."' WHERE id = '".$_POST['id']."'";	
		                $class->consulta($sql);
		                $dataFile = generarXMLCDATA($respuesta);		                
		                $doc = new DOMDocument('1.0', 'UTF-8');
	        			$doc->loadXML($dataFile);//xml	 
	        			if($doc->save('../comprobantes/'.$numeroAutorizacion.'.xml')) {
	        				/*include '../generarPDF.php';		        				
		        			$data = correo($fecha,$totalComprobante,'./comprobantes/'.$numeroAutorizacion.'.xml','../comprobantes/'.$numeroAutorizacion.'.pdf',$_POST['nombreComercial'],$mail,$doc->saveXML(),generarPDF($id),0);*/
		        			if(trim($email) == '') {		
								$sql = "update comprobanteretencion set estado = '1' where id = '".$_POST['id']."'";			
								if($class->consulta($sql)) {
									$data = 1;///datos actualizados
								} else {
									$data = 4;//error al momento de guadar
								}
							} else {
								$data = 3; ///error al momento de enviar el correo
								$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";
			    				$class->consulta($sql);	
							}	
	        			} else {
	        				$data = 2;
	        				$sql = "UPDATE comprobanteRetencion SET estado = '".$data."' WHERE id = '".$_POST['id']."'";////ERROR AL GENERAR LOS DOCUMENTOS
		                	$class->consulta($sql);			                	
	        			}      
					} else {
						$data = 7;
						//$data =	$respuesta['RespuestaRecepcionComprobante']['estado'];
						$sql = "UPDATE comprobanteRetencion SET estado = '7' WHERE id = '".$_POST['id']."'";////rechazado	
            			$class->consulta($sql);	
					}
				} else {
					//$data = $respWeb['RespuestaRecepcionComprobante']['comprobantes']['comprobante']['mensajes']['mensaje']['mensaje'];
					$data = 8;
					$sql = "UPDATE comprobanteRetencion SET estado = '8' WHERE id = '".$_POST['id']."'";	//ERROR EN EL WEB SERVICE
	    			$class->consulta($sql);
				}
				
				/*$nuevaClave = $class->generarClave($_POST['id'],$codDoc,$ruc,$ambiente,$establecimiento.''.$puntoEmision,$secuencial,$fecha2,$emision);///vuelvo a generar la clave
				$sql = "update comprobanteRetencion SET fechaEmision = '".$fecha2."', claveAcceso = '".$nuevaClave."' where id = '".$_POST['id']."'";
				$class->consulta($sql);*/
			
			}
		}			
		echo $data;
	}		
?>	