<?php
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function jwtEncode($id)
{
    $secret_key = 'HASAN_OZDEMIR_SECRETKEY_44704470';
    try {
        $issuer_claim = "localhost"; // this can be the servername
        $audience_claim = "localhost";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 60 * 60 * 24 * 365; // expire 1 year
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array("id" => $id)
        );
        $jwt = JWT::encode($token, $secret_key, 'HS256');
        return $jwt;
    } catch (Exception $err) {
        http_response_code(401);
        return "Access denied.";
    }
}

function jwtDecode($jwt)
{
    $secret_key = 'HASAN_OZDEMIR_SECRETKEY_44704470';

    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        http_response_code(200);
        return $decoded;
    } catch (Exception $err) {
        http_response_code(401);
        return "Access denied.";
    }
}
