<?php
	include 'includes/session.php';
    try {
        $sql = "SELECT DISTINCT  ca.firstname as nombre,ca.lastname as apellido,count(vo.candidate_id) as cantidad FROM candidates ca INNER JOIN votes vo ON vo.candidate_id = ca.id GROUP BY ca.firstname,ca.lastname";
		$query = $conn->query($sql);
		$data  = [];
        while ($row = $query->fetch_assoc()) {
            $data [] = $row;
        }
	    echo json_encode(['data' => $data]);
    } catch (\Throwable $th) {
        //throw $th;
        echo json_encode(['error' => $th->getMessage()]);
    }
exit();
