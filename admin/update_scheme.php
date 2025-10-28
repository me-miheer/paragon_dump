<?php
require_once("checkLogin.php");
require('../connection.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $scheme = mysqli_real_escape_string($mysql, $_POST['scheme']);

    $allowedSchemes = ['Scheme 1', 'Scheme 2', 'Scheme 3', 'Scheme 4'];

    if (!in_array($scheme, $allowedSchemes)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid scheme']);
        exit;
    }

    $query = "UPDATE dumpv2 SET scheme = '$scheme' WHERE id = $id";
    $result = mysqli_query($mysql, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($mysql)]);
    }
}
