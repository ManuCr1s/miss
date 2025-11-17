<?php
	include 'includes/conn.php';
	session_start();
	$response = ['status' => 'error', 'message' => 'Algo salió mal'];
	if(isset($_POST['dni'], $_POST['firstname'], $_POST['lastname'], $_POST['password'])){
		$dni= $_POST['dni'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

		// generate voters id
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);

		// check if DNI exists
		$check = $conn->query("SELECT * FROM voters WHERE dni = '$dni'");
		if($check->num_rows > 0){
			$response['message'] = 'El DNI ya está registrado.';
		} else {
			$sql = "INSERT INTO voters (voters_id,dni, password, firstname, lastname) VALUES ('$voter','$dni','$password', '$firstname', '$lastname')";
			if($conn->query($sql)){
				$response['status'] = 'success';
				$response['message'] = 'Votante agregado correctamente. Su ID de votante es: '.$voter.' no olvide ingresar correctamente su contraseña';
			} else {
				  $response['message'] = $conn->error;
			}
		}

	} else {
		 $response['message'] = 'Llena todos los campos primero.';
	}
echo json_encode($response);
exit();
?>