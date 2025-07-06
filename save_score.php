<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: text/plain; charset=utf-8");

setcookie('sessionid', 'abc123', [
    'expires' => time() + 3600,
    'path' => '/',
    'domain' => '.cbloc.wuaze.com',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

// Připojení k databázi
$host = "sql8.freesqldatabase.com";
$dbname = "sql8788580";
$user = "sql8788580";
$pass = "DpeBYwi8eV";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    http_response_code(500);
    echo "Chyba připojení k databázi.";
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['username']) || !isset($data['score'])) {
    http_response_code(400);
    echo "Neplatná data.";
    exit;
}

$username = $conn->real_escape_string(trim($data['username']));
$score = (int)$data['score'];

$sql = "INSERT INTO userScore (username, score) VALUES ('$username', $score)";
if ($conn->query($sql) === TRUE) {
    echo "Skóre uloženo!";
} else {
    http_response_code(500);
    echo "Chyba při ukládání skóre.";
}

$conn->close();