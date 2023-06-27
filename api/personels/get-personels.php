<?php
include_once '../config/database.php';
include_once '../config/jwtEncDec.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$table_name = 'personels';
$conn = null;
$jwt = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

try {
    $data = json_decode(file_get_contents("php://input"));
    $headers = apache_request_headers();
    $jwt = $headers['authorization'];
    if ($jwt) {
        if (jwtDecode($jwt)) {
            $query = "SELECT * FROM " . $table_name . "";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                echo json_encode(array(
                    "status" => 0,
                    "message" => $result,
                ));
            } else {
                http_response_code(200);
                return json_encode(array(
                    "status" => 1,
                    "message" => "No Data!."
                ));
            }
        }
    } else {
        http_response_code(400);
        echo json_encode(array(
            "status" => 2,
            "message" => "Token Error!"
        ));
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 3,
        'message' => $e->getMessage(),
    ]);
}
