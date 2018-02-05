<?php
	if (is_file("../../../dist/fpdf/rotation.php")){
	    require_once("../../../dist/fpdf/rotation.php");
	}else{
		require_once("../../../../dist/fpdf/rotation.php");
	}
	if (is_file("../../../dist/fpdf/barcode.inc.php")){
	    require_once("../../../dist/fpdf/barcode.inc.php");
	}else{
		require_once("../../../../dist/fpdf/barcode.inc.php");
	}
	if (is_file("../../../admin/class.php")){
	    require_once("../../../admin/class.php");
	}else{
		require_once("../../../../admin/class.php");
	}

	class PDF extends PDF_Rotate {   
	    var $widths;
	    var $aligns;       
	    function SetWidths($w) {            
	        $this->widths = $w;
	    }  

	    function Header() {                         
	        $this->AddFont('Amble-Regular','','Amble-Regular.php');
	        $this->SetFont('Amble-Regular','',10);        
	        $fecha = date('Y-m-d', time());           
	        $this->SetY(1);
	        $this->Cell(20, 5, 'Generado: '.$fecha, 0,0, 'C', 0);                                                             
	        $this->Cell(178, 5, 'Accion Imbaburapak', 0,0, 'R', 0);                                                             
	        $this->Ln(7);
	        $this->SetX(13);
	        if (is_file("../../../dist/fpdf/logo.fw.png")) {
			    $this->RotatedImage('../../../dist/fpdf/logo.fw.png', 50, 150, 100, 80, 45);
			} else {
				$this->RotatedImage('../../../../dist/fpdf/logo.fw.png', 50, 150, 100, 80, 45);
			}
	                            
	        $this->SetX(0);            
		}

	    function Footer() {            
	        $this->SetY(-10);            
	        $this->SetFont('Arial','I',8);            
	        $this->Cell(0,10,'Pag. '.$this->PageNo().'/{nb}',0,0,'C');
	    }

	   	function RotatedImage($file, $x, $y, $w, $h, $angle) {            
	        $this->Rotate($angle, $x, $y);
	        $this->Image($file, $x, $y, $w, $h);
	        $this->Rotate(0);
	    }      		         
	}

	if(isset($_GET['id'])) {		
		$id = $_GET['id'];
		generarPDF($id);
	}

	function generarPDF($id) {
		$class = new constante();	
		$sql = "select E.ruc, CR.numeroComprobante,CR.numeroAutorizacion, CR.fechaEmision, CR.claveAcceso, E.razonSocial, E.nombreComercial, E.direccion direccionMatriz, S.direccion direccionEstablecimiento, E.contribuyente, E.obligacion, C.razonSocial, C.identificacion, C.direccion, C.telefono, C.email, TC.nombre, CR.secuencial, CR.establecimiento, CR.puntoEmision, CR.fechaAutorizacion, CR.ambiente, CR.emision from comprobanteretencion CR inner join sucursal S on CR.idSucursal = S.id inner join empresa E ON S.idEmpresa = E.id inner join contribuyente C on CR.idContribuyente = C.id inner join tipocomprobante TC on CR.idTipoComprobante = TC.id inner join tipoidentificacion TI on C.idTipoIdentificacion = TI.id where CR.id = '".$id."'";
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
			$ambiente = $row[21];
			$emision = $row[22];
		}		

		$sql = "select nombre from tipoemision where codigo = '".$emision."'";
		$sql = $class->consulta($sql);
		while ($row = $class->fetch_array($sql)) {
			$emision = $row[0];
		}

		$sql = "select nombre from tipoambiente where codigo = '".$ambiente."'";
		$sql = $class->consulta($sql);
		while ($row = $class->fetch_array($sql)) {
			$ambiente = $row[0];
		}


		$ceros = 9;
		$temp = '';
		$tam = $ceros - strlen($secuencial);
	  	for ($i = 0; $i < $tam; $i++) {                 
	    	$temp = $temp .'0';        
	  	}
	  	$secuencial = $temp .''. $secuencial;

		$pdf = new PDF('P','mm','a4');
		$pdf->AddPage();
		$pdf->SetMargins(10,0,0,0);        
		$pdf->AliasNbPages();
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->AddFont('Amble-Regular','','Amble-Regular.php');
		$pdf->SetFont('Amble-Regular','',10); 

		$pdf->Rect(3, 8, 100, 36 ,1, 'D');//1 empresa imagen
		if (is_file("../../../dist/fpdf/logoac.png")){
		    $pdf->Image('../../../dist/fpdf/logoac.png',5,20,90,15);   /// en caso de multiempresa  
		}else{
			$pdf->Image('../../../../dist/fpdf/logoac.png',5,20,90,15);   /// en caso de multiempresa
		}		
		$pdf->Rect(3, 45, 100, 53 , 'D');//2 datos personales
		$pdf->Text(108, 15, 'R U C :'. $ruc);//ruc		 	
		$pdf->Text(108, 23, utf8_decode("COMPROBANTE DE RETENCIÓN"));//tipo comprobante
		$pdf->Text(108, 31, 'No. '. $establecimiento.'-'.$puntoEmision.'-'.$secuencial);//tipo comprobante
		$pdf->Text(108, 39, utf8_decode('NÚMERO DE AUTORIZACIÓN'));//nro autorizacion TEXT
		$pdf->SetY(40);
		$pdf->SetX(107);	
		$pdf->Multicell(100, 5, $numeroAutorizacion,0);//nro autorizacion		
		$pdf->Text(108, 55, utf8_decode('FECHA Y HORA DE AUTORIZACIÓN'));//fecha y hora de autorizacion
		$pdf->Text(108, 61, $fechaAut);//FECHA
		$pdf->Text(108, 68, utf8_decode('AMBIENTE: '.$ambiente));//ambiente
		$pdf->Text(108, 75, utf8_decode('EMISIÓN: '.$emision));//tipo de emision
		$pdf->Text(108, 81, utf8_decode('CLAVE DE ACCESO: '));//clave de acceso
		$code_number = $claveAcceso;//////cpdigo de barras		
		new barCodeGenrator($code_number,1,'temp.gif', 470, 60, true);///img codigo barras	
		$pdf->Image('temp.gif',108,83,96,15);     	

		$pdf->Rect(106, 8, 102, 90 , 'D');//3 DATOS EMPRESA	 
		$pdf->SetY(46);
		$pdf->SetX(4);
		$pdf->multiCell( 98,5, $razonSocial,0 );//NOMBRE proveedor	
		//$pdf->SetY(56);
		//$pdf->SetX(4);	
		//$pdf->multiCell( 98,5, $nombreComercial ,0 );//NOMBRE proveedor	
		$pdf->SetY(66);	
		$pdf->SetX(4);	
		$pdf->multiCell( 98, 5, 'Dir Matriz: '.$direcionMatriz,0 );//	 direccion	
		$pdf->SetY(76);	
		$pdf->SetX(4);	
		$pdf->multiCell( 98, 5, 'Dir Sucursal: '.$direccionEstablecimiento,0 );//	 direccion	
		//$pdf->Text(5, 90, utf8_decode('Contribuyente Especial Resolución Nro: '.$nroContribuyente));//contribuyente
		$pdf->Text(5, 96, utf8_decode('Obligado a llevar Contabilidad: '.$obligado));//obligado
		$pdf->Rect(3, 101, 205, 20 , 'D');////4 INFO TRIBUTARIA			     
	 	$pdf->SetY(101);
		$pdf->SetX(3);
		$pdf->multiCell( 130, 6, utf8_decode('Razón Social / Nombres y Apellidos: '.$contribuyente ),0 );//NOMBRE cliente	
		$pdf->Text(135, 105, utf8_decode('RUC / CI: '.$identificacion));//ruc cliente
		$pdf->Text(5, 117, utf8_decode('Fecha de Emisión: '.$fechaEmision));//fecha de emision cliente
		$pdf->Text(136, 117, utf8_decode('Guía de Remisión: ' ));//guia remision 

		   //////////////////detalles factura/////////////
	    $pdf->SetFont('Amble-Regular','',9);               
	    $pdf->SetY(123);
		$pdf->SetX(3);
		$pdf->multiCell( 50, 10, utf8_decode('Comprobante'),1 );
		$pdf->SetY(123);
		$pdf->SetX(53);
		$pdf->multiCell( 32, 10, utf8_decode('Número'),1 );
		$pdf->SetY(123);
		$pdf->SetX(85);
		$pdf->multiCell( 20, 5, utf8_decode('Fecha Emisión'),1 );
		$pdf->SetY(123);
		$pdf->SetX(105);
		$pdf->multiCell( 15, 5, utf8_decode('Ejercicio Fiscal'),1 );
		$pdf->SetY(123);
		$pdf->SetX(120);
		$pdf->multiCell( 28, 5, utf8_decode('Base Imponible para la Retención'),1 );
		$pdf->SetY(123);
		$pdf->SetX(148);
		$pdf->multiCell( 20, 10, utf8_decode('Impuesto'),1 );
		$pdf->SetY(123);
		$pdf->SetX(168);
		$pdf->multiCell( 20, 5, utf8_decode('Porcentaje Retención'),1 );
		$pdf->SetY(123);
		$pdf->SetX(188);
		$pdf->multiCell( 20, 5, utf8_decode('Valor Retenido'),1 );
		
		////DETALLES COMPROBANTE////
		$sql = "select CR.ejercicioFiscal, CD.baseImponible, R.nombre, CD.porcentaje, CD.valorRetenido from comprobanteretencion CR inner join detallecomprobanteretencion CD on CR.id = CD.idComprobanteRetencion inner join tiporetencion R on CD.idCodigoImpuesto = R.id inner join tarifaretencion TR on CD.idCodigoRetencion = TR.id where CR.id = '".$id."'";
		$sql = $class->consulta($sql);
		$x = 133;
		$y = 3;
		while ($row = $class->fetch_array($sql)) {			
			$pdf->SetY($x);
			$pdf->SetX(3);
			$comprobante = utf8_decode($tipoDocumento);
			if(strlen($comprobante) > 25)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(50, $tam, $comprobante,1);

			$pdf->SetY($x);
			$pdf->SetX(53);
			$numero = utf8_decode($numeroComprobante);
			if(strlen($numero) > 19)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(32, $tam, $numero,1);

			$pdf->SetY($x);
			$pdf->SetX(85);
			$fechaEmision = utf8_decode($fechaEmision);
			if(strlen($fechaEmision) > 10)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(20, $tam, $fechaEmision,1);

			$pdf->SetY($x);
			$pdf->SetX(105);
			$ejercicioFiscal = utf8_decode($row[0]);
			if(strlen($ejercicioFiscal) > 10)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(15, $tam, $ejercicioFiscal,1);
			
			$pdf->SetY($x);
			$pdf->SetX(120);
			$baseImponible = utf8_decode($row[1]);
			if(strlen($baseImponible) > 19)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(28, $tam, $baseImponible,1);

			$pdf->SetY($x);
			$pdf->SetX(148);
			$impuesto = utf8_decode($row[2]);
			if(strlen($impuesto) > 15)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(20, $tam, $impuesto,1);

			$pdf->SetY($x);
			$pdf->SetX(168);
			$porcentaje = utf8_decode($row[3]);
			if(strlen($porcentaje) > 10)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(20, $tam, $porcentaje,1);

			$pdf->SetY($x);
			$pdf->SetX(188);
			$valorRetenido = utf8_decode($row[4]);
			if(strlen($valorRetenido) > 15)
				$tam = 5;
			else
				$tam = 10;	
			$pdf->multiCell(20, $tam, $valorRetenido,1);	

			$x = $x + 10;
		}						
		/////////////////pie de pagina//////////	           	
		$pdf->Ln(5);
		$pdf->SetX(3);	
	    $pdf->Rect($pdf->GetX(), $pdf->GetY(), 100, 55 , 'D');////3 INFO ADICIONAL
		$y =  $pdf->GetY();
		$x =  $pdf->GetX();	
		$pdf->Text($x + 5, $y + 5, utf8_decode('INFORMACIÓN ADICIONAL')); // informacion 		
		$pdf->SetY($y + 7);
		$pdf->SetX($x);
		$pdf->multiCell( 100, 5, utf8_decode("Dirección:".$direcion),0);
		$pdf->SetY($y + 17);
		$pdf->SetX($x);
		$pdf->multiCell( 100, 5, utf8_decode("Teléfono: ".$telefono),0);
		$pdf->SetY($y + 29);
		$pdf->SetX($x);
		$pdf->multiCell( 100, 5, utf8_decode("Email: ".$email ),0);

		if(isset($_GET['id'])) {
			$pdf->Output();		
		} else {
			$pdf_file_contents = $pdf->Output("","S");		
			return $pdf_file_contents;
		}
	}
?>