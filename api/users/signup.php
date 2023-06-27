<?php
include_once '../config/database.php';
include_once '../config/jwtEncDec.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$table_name = 'users';
$conn = null;
$jwt = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

try {
    $data = json_decode(file_get_contents("php://input"));
    $name = $data->name;
    $email = $data->email;
    $pass = $data->pass;

    $query = "SELECT '$email' FROM " . $table_name . " WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $num = $stmt->rowCount();
    if ($num > 0) {
        echo json_encode(array(
            "status" => 2,
            "message" => "Email Allready Exist."
        ));
    } else {
        $query = "INSERT INTO " . $table_name . " SET name = :name, email = :email, pass = :pass";
        $stmt = $conn->prepare($query);
        $pass_hash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $pass_hash);
        if ($stmt->execute()) {
            http_response_code(200);
            $id = $conn->lastInsertId();
            echo json_encode(array(
                "status" => 0,
                "id" => $id,
                "message" => jwtEncode($id),
            ));
        } else {
            http_response_code(400);
            echo json_encode(array(
                "status" => 1,
                "id" => -1,
                "message" => "Unable to register the user!"
            ));
        }
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 3,
        'message' => $e->getMessage(),
    ]);
}
