<?php 
	if(!isset($_SESSION)){
        session_start();        
    }
	include_once('../../admin/class.php');
	$class = new constante();
	$fecha = $class->fecha_hora();

	// contador tipo retencion
	$id_tipo_retencion = 0;
	$resultado = $class->consulta("SELECT max(id) FROM tipo_retencion");
	while ($row = $class->fetch_array($resultado)) {
		$id_tipo_retencion = $row[0];
	}
	$id_tipo_retencion++;
	// fin

	if ($_POST['oper'] == "add") {
		$resultado = $class->consulta("SELECT count(*) FROM tipo_retencion WHERE nombre_tipo_retencion = '$_POST[nombre_tipo_retencion]'");
		while ($row = $class->fetch_array($resultado)) {
			$data = $row[0];
		}

		if ($data != 0) {
			$data = "3";
		} else {
			$resp = $class->consulta("INSERT INTO tipo_retencion VALUES ('$id_tipo_retencion','$_POST[codigo]','$_POST[nombre_tipo_retencion]','$_POST[principal]','$_POST[observaciones]','1','$fecha');");
			$data = "1";
		}
	} else {
	    if ($_POST['oper'] == "edit") {
	    	$resultado = $class->consulta("SELECT count(*) FROM tipo_retencion WHERE nombre_tipo_retencion = '$_POST[nombre_tipo_retencion]' AND id NOT IN ('$_POST[id]')");
			while ($row = $class->fetch_array($resultado)) {
				$data = $row[0];
			}

			if ($data != 0) {
			 	$data = "3";
			} else {
				$resp = $class->consulta("UPDATE tipo_retencion SET codigo = '$_POST[codigo]',nombre_tipo_retencion = '$_POST[nombre_tipo_retencion]',principal = '$_POST[principal]',observaciones = '$_POST[observaciones]',fecha_creacion = '$fecha' WHERE id = '$_POST[id]'");
	    		$data = "2";
			}
	    } else {
	    	if ($_POST['oper'] == "del") {
	    		$resp = $class->consulta("UPDATE tipo_retencion SET estado = '0' WHERE id = '$_POST[id]'");
	    		$data = "4";	
	    	}
	    }
	}

	echo $data;
?>