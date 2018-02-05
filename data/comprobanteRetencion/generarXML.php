<?php
	function generarXML($id,$codDoc,$ambiente,$emision){
		$class = new constante();	
		$sql = "select E.ruc, CR.numeroComprobante,CR.numeroAutorizacion, CR.fechaEmision, CR.claveAcceso, E.razonSocial, E.nombreComercial, E.direccion direccionMatriz, S.direccion direccionEstablecimiento, E.contribuyente, E.obligacion, C.razonSocial, C.identificacion, C.direccion, C.telefono, C.email, TC.codigo, CR.secuencial, CR.establecimiento, CR.puntoEmision, CR.fechaAutorizacion, TI.codigo, CR.ejercicioFiscal from comprobanteretencion CR inner join sucursal S on CR.idSucursal = S.id inner join empresa E ON S.idEmpresa = E.id inner join contribuyente C on CR.idContribuyente = C.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join tipoidentificacion TI on C.idTipoIdentificacion = TI.id where CR.id = '".$id."'";
		$sql = $class->consulta($sql);
		while ($row = $class->fetch_array($sql)) {
			$ruc = $row[0];
			$numeroComprobante = $row[1];
			$numeroAutorizacion = $row[2];
			$fechaEmision = $row[3];
			$claveAcceso = $row[4];
			$razonSocial = $row[5];
			$nombreComercial = $row[6];
			$direcionMatriz = $row[7];
			$direccionEstablecimiento = $row[8];
			$nroContribuyente = $row[9];
			$obligado = $row[10];
			$contribuyente = $row[11];
			$identificacion = $row[12];
			$direcion = $row[13];
			$telefono = $row[14];
			$email = $row[15];
			$tipoDocumento = $row[16];
			$secuencial = $row[17];
			$establecimiento = $row[18];
			$puntoEmision = $row[19];
			$fechaAut = $row[20];
			$tipoIdentificacion = $row[21];
			$ejercicioFiscal = $row[22];
		}							
		$ceros = 9;
		$temp = '';
		$tam = $ceros - strlen($secuencial);
      	for ($i = 0; $i < $tam; $i++) {                 
        	$temp = $temp .'0';        
      	}
      	$secuencial = $temp .''. $secuencial ;
      	$s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<comprobanteRetencion id=\"comprobante\" version=\"1.0.0\">\n";		
			$s .= "<infoTributaria>\n";
				$s .= "<ambiente>".$ambiente."</ambiente>\n";
				$s .= "<tipoEmision>".$emision."</tipoEmision>\n";
				$s .= "<razonSocial>".substr($razonSocial, 0,300) ."</razonSocial>\n";
				$s .= "<nombreComercial>".substr($nombreComercial, 0,300)."</nombreComercial>\n";
				$s .= "<ruc>".substr($ruc,0,13)."</ruc>\n";
				$s .= "<claveAcceso>".substr($claveAcceso,0,49)."</claveAcceso>\n";
				$s .= "<codDoc>".substr($codDoc,0,2)."</codDoc>\n";
				$s .= "<estab>".substr($establecimiento,0,3)."</estab>\n";
				$s .= "<ptoEmi>".substr($puntoEmision,0,3)."</ptoEmi>\n";
				$s .= "<secuencial>".substr($secuencial,0,9)."</secuencial>\n";
				$s .= "<dirMatriz>".substr($direcionMatriz,0,300)."</dirMatriz>\n";
			$s .= "</infoTributaria>\n";
			$s .= "<infoCompRetencion>\n";
				$s .= "<fechaEmision>".substr($fechaEmision,0,10)."</fechaEmision>\n";
				$s .= "<dirEstablecimiento>".substr($direccionEstablecimiento,0,300)."</dirEstablecimiento>\n";
				if($nroContribuyente != '')
					$s .= "<contribuyenteEspecial>".substr($nroContribuyente,0,13)."</contribuyenteEspecial>\n";
				$s .= "<obligadoContabilidad>".$obligado."</obligadoContabilidad>\n";
				$s .= "<tipoIdentificacionSujetoRetenido>".substr($tipoIdentificacion,0,2)."</tipoIdentificacionSujetoRetenido>\n";
				$s .= "<razonSocialSujetoRetenido>".substr($contribuyente,0,300)."</razonSocialSujetoRetenido>\n";
				$s .= "<identificacionSujetoRetenido>".substr($identificacion,0,30)."</identificacionSujetoRetenido>\n";
				$s .= "<periodoFiscal>".substr($ejercicioFiscal,0,7)."</periodoFiscal>\n";
			$s .= "</infoCompRetencion>\n";

			$sql = "select CR.ejercicioFiscal, CD.baseImponible, R.nombre, CD.porcentaje, CD.valorRetenido,R.codigo, TR.codigo from comprobanteretencion CR inner join detallecomprobanteretencion CD on CR.id = CD.idComprobanteRetencion inner join tiporetencion R on CD.idCodigoImpuesto = R.id inner join tarifaretencion TR on CD.idCodigoRetencion = TR.id where CR.id = '".$id."'";
			//echo $sql;
			$sql = $class->consulta($sql);

			$s .= "<impuestos>\n";	
			while ($row = $class->fetch_array($sql)) {				
				$s .= "<impuesto>\n";
					$s .= "<codigo>".substr($row[5],0,1)."</codigo>\n";
					$s .= "<codigoRetencion>".substr($row[6],0,5)."</codigoRetencion>\n";
					$s .= "<baseImponible>".number_format($row[1], 2, '.', '')."</baseImponible>\n";
					$s .= "<porcentajeRetener>".number_format($row[3], 2, '.', '')."</porcentajeRetener>\n";
					$s .= "<valorRetenido>".number_format($row[4], 2, '.', '')."</valorRetenido>\n";
					$s .= "<codDocSustento>".substr($tipoDocumento,0,2)."</codDocSustento>\n";
					$s .= "<numDocSustento>".substr($numeroComprobante,0,15)."</numDocSustento>\n";
					$s .= "<fechaEmisionDocSustento>".substr($fechaEmision,0,10)."</fechaEmisionDocSustento>\n";
				$s .= "</impuesto>\n";
			}	
			$s .= "</impuestos>\n";		
			$s .= "<infoAdicional>\n";
				$s .= "<campoAdicional nombre=\"DIRECCION\">".' '.substr($direcion,0,299)."</campoAdicional>\n";	
				$s .= "<campoAdicional nombre=\"TELEFONO\">".' '.utf8_decode(substr($telefono,0,299))."</campoAdicional>\n";	
				$s .= "<campoAdicional nombre=\"EMAIL\">".' '.utf8_decode(substr($email,0,299))."</campoAdicional>\n";					
			$s .= "</infoAdicional>";		
		$s .="\n</comprobanteRetencion>";		
		return $s;		
	}
	function generarXMLCDATA($data){				
      	$s = "";
		$s = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		$s .= "<autorizacion>\n";
			$s .= "<estado>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->estado."</estado>\n";
			$s .= "<numeroAutorizacion>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->numeroAutorizacion."</numeroAutorizacion>\n";
			$s .= "<fechaAutorizacion>".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->fechaAutorizacion."</fechaAutorizacion>\n";
			$s .= "<comprobante><![CDATA[".$data->RespuestaAutorizacionComprobante->autorizaciones->autorizacion->comprobante."]]></comprobante>";
		 	$s .= "<mensajes/>\n";
		$s .= "</autorizacion>";
		return $s;
	}
?>