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
    $email = $data->email;
    $pass = $data->pass;

    $query = "SELECT id, name, pass FROM " . $table_name . " WHERE email = ? LIMIT 0,1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $name = $row['name'];
        $pass2 = $row['pass'];
        if (password_verify($pass, $pass2)) {
            http_response_code(200);
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
                "message" => "Login failed.",
            ));
        }
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 3,
        'message' => $e->getMessage(),
    ]);
}
