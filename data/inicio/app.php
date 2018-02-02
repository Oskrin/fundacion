<?php 
	include_once('../../admin/class.php');
	$class = new constante();
	session_start(); 
	error_reporting(0);

	$fecha = $class->fecha_hora();
	$fecha_corta = $class->fecha();

	// informacion ingresos usuarios
	if (isset($_POST['cargar_informacion'])) {
		$resultado = $class->consulta("SELECT usuario, fecha_creacion FROM usuarios WHERE id = '".$_SESSION['user']['id']."'");
		while ($row = $class->fetch_array($resultado)) {
			$data = array('usuario' => $row[0], 'fecha_creacion' => substr($row[1], 0, -6));
		}
		echo $data = json_encode($data);
	}
	// fin

	// informacion cargar chat
	if (isset($_POST['cargar_chat'])) {
		$resultado = $class->consulta("SELECT U.nombres_completos, U.imagen, C.mensaje, C.fecha_creacion FROM chat C, usuarios U WHERE C.id_usuario = U.id ORDER BY C.id ASC");
		while ($row = $class->fetch_array($resultado)) {
			$data[] = array('nombres_completos' => $row[0], 'imagen' => $row[1], 'mensaje' => $row[2], 'fecha' => substr($row[3], 0, -6));
		}
		echo $data = json_encode($data);
	}
	// fin

	// cargar usuarios conectados
	if (isset($_POST['guardar_chat'])) {
		// contador chat
		$id_chat = 0;
		$resultado = $class->consulta("SELECT max(id) FROM chat");
		while ($row = $class->fetch_array($resultado)) {
			$id_chat = $row[0];
		}
		$id_chat++;
		// fin
		$fecha = $class->fecha_hora();

		$resp = $class->consulta("INSERT INTO chat VALUES  (	'$id_chat',
																'".$_SESSION['user']['id']."',
																'$_POST[mensaje]',
																'1',
																'$fecha')");
		
		$data = 1;
		echo $data;
	}
	// fin
?>