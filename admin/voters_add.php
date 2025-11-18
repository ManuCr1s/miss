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
			/* $token = 'sk_8431.V90eyloQeOKiJJGJMikpimCfpKcd9jWD'; */
			$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIzOTg4MiIsImh0dHA6Ly9zY2hlbWFzLm1pY3Jvc29mdC5jb20vd3MvMjAwOC8wNi9pZGVudGl0eS9jbGFpbXMvcm9sZSI6ImNvbnN1bHRvciJ9.LnnxT69Mll6bmJN0vcj-rntearWB4-dEo0vd_pvOppU';
			$curl = curl_init();
			curl_setopt_array($curl, [
				CURLOPT_URL => "https://api.factiliza.com/v1/dni/info/".$key,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => [
					"Authorization: Bearer $token"
				],
			]);
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
