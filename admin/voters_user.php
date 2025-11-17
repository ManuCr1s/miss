<?php
	include 'includes/conn.php';
	session_start();
	$response = ['status' => '0', 'message' => 'Algo salió mal'];
	$response=['dni' =>$_POST['nombre']];
	if(isset($_POST['dni'], $_POST['nombre'], $_POST['apellidos'])){
		$dni= $_POST['dni'];
		$nombre = $_POST['nombre'];
		$apellidos = $_POST['apellidos'];
		$password = password_hash($_POST['dni'], PASSWORD_DEFAULT);

		// generate voters id
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);
		$sql = "INSERT INTO voters (voters_id,dni, password, firstname, lastname) VALUES ('$voter','$dni','$password', '$nombre', '$apellidos')";
			if($conn->query($sql)){
				$response['status'] = 'success';
				$response['message'] = 'Su contraseña es su DNI';
			} else {
				  $response['message'] = $conn->error;
			}

	} else {
		 $response['message'] = 'Llena todos los campos primero.';
	}
echo json_encode($response);
exit();
