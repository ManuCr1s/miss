<?php
	include 'includes/conn.php';
	session_start();
	$response = ['status' => '0', 'message' => 'Algo salió mal'];
	if(isset($_POST['dni'])){
		$dni= $_POST['dni'];
		$check = $conn->query("SELECT * FROM voters WHERE dni = '$dni'");
		if($check->num_rows > 0){
			$response['message'] = 'El DNI ya está registrado.';
		}else{
			$key = $_POST['dni'];
			$token = 'sk_8431.V90eyloQeOKiJJGJMikpimCfpKcd9jWD';
			$curl = curl_init();
			curl_setopt_array($curl, array(
				// para user api versión 2
				CURLOPT_URL => 'https://api.decolecta.com/v1/reniec/dni?numero=' . $key,
				// para user api versión 1
				// CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $dni,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 2,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
				'Referer: https://apis.net.pe/consulta-dni-api',
				'Authorization: Bearer ' . $token
				),
			));
			$apiresponse = curl_exec($curl);
			curl_close($curl);
			$apiresponse = json_decode($apiresponse, true);
			$response = ['status' => '1', 'apiresponse' =>$apiresponse];
		}
	}else{
		$response ['status'] = '2';
		$response['message'] = 'Ingrese datos del dni';
	}
/* 	if(isset($_POST['dni'], $_POST['firstname'], $_POST['lastname'], $_POST['password'])){
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
	} */
echo json_encode($response);
exit();
