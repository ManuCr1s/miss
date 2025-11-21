<?php
	include 'includes/conn.php';
	session_start();
	$response = ['status' => '0', 'message' => 'Algo salió mal'];
	$response=['dni' =>$_POST['nombre']];
	if(isset($_POST['dni'], $_POST['nombre'], $_POST['apellidos'])){
		$dni= trim($_POST['dni']);
		$nombre = trim($_POST['nombre']);
		$apellidos = trim($_POST['apellidos']);
		 	if($dni === "" || $nombre === "" || $apellidos === "" || $paterno === ""){
				$response['message'] = 'Ningún campo puede estar vacío.';
				echo json_encode($response);
				exit();
			}
		$password = password_hash(strtoupper($_POST['paterno']), PASSWORD_DEFAULT);
		// generate voters id
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);
		$sql = "INSERT INTO voters (voters_id,dni, password, firstname, lastname) VALUES (?, ?, ?, ?, ?)";
		if($stmt = $conn->prepare($sql)){
				$stmt->bind_param("sssss", $voter, $dni, $password, $nombre, $apellidos);

				if($stmt->execute()){
					$response['status']  = 'success';
					$response['message'] = 'Su contraseña es su APELLIDO PATERNO EN MAYÚSCULAS';
				} else {
					$response['message'] = "Error al ejecutar: " . $stmt->error;
				}

				$stmt->close();
		} else {
			$response['message'] = "Error al preparar: " . $conn->error;
		}

	} else {
		 $response['message'] = 'Llena todos los campos primero.';
	}
echo json_encode($response);
exit();
