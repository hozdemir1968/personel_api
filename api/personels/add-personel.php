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
    $name = $data->name;
    $surname = $data->surname;
    $citizenNo = $data->citizenNo;
    $gender = $data->gender;
    $phone = $data->phone;
    $email = $data->email;
    $birthDate = $data->birthDate;
    $employmentDate = $data->employmentDate;
    $sgkType = $data->sgkType;
    $department = $data->department;
    $duty = $data->duty;
    $iban = $data->iban;

    $allheaders = getallheaders();
    $jwt = $allheaders['authorization'];
    if ($jwt) {
        $userId = $data->userId;
        $title = $data->title;
        $content = $data->content;
        $query = "INSERT INTO " . $table_name . " SET name = :name, surname = :surname, citizenNo = :citizenNo, gender = :gender, phone = :phone, email = :email, birthDate = :birthDate, employmentDate = :employmentDate, sgkType = :sgkType, department = :department, duty = :duty, iban = :iban";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':citizenNo', $citizenNo);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->bindParam(':employmentDate', $employmentDate);
        $stmt->bindParam(':sgkType', $sgkType);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':duty', $duty);
        $stmt->bindParam(':iban', $iban);
        if ($stmt->execute()) {
            http_response_code(200);
            $id = $conn->lastInsertId();
            echo json_encode(array(
                "status" => 0,
                "message" => 'Data Accepted',
            ));
        } else {
            http_response_code(400);
            echo json_encode(array(
                "status" => 1,
                "message" => "Unable to add data."
            ));
        }
    } else {
        echo json_encode(array(
            "status" => 2,
            "message" => 'Token Error!',
        ));
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 3,
        'message' => $e->getMessage(),
    ]);
}
