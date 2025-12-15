<?php
header("Content-Type: application/json");

$host = "mysql-db";
$user = "user1";
$pass = "pass123";
$dbname = "age_calculator";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

$action = $_GET['action'] ?? '';

if ($action === 'save') {
    $data = json_decode(file_get_contents("php://input"), true);

    $day = $data["day"];
    $month = $data["month"];
    $year = $data["year"];
    $age_text = $data["age"];

    $stmt = $conn->prepare("INSERT INTO ages (day, month, year, age_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $day, $month, $year, $age_text);
    $stmt->execute();

    echo json_encode(["message" => "Saved successfully"]);
    exit;
}

if ($action === 'list') {
    $result = $conn->query("SELECT * FROM ages ORDER BY id DESC");
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($rows);
    exit;
}

echo json_encode(["error" => "Invalid action"]);
?>

