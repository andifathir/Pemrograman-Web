<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = "";     // Replace with your DB password
$dbname = "latihan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

if ($request[0] === 'tasks') {
    switch ($method) {
        case 'GET':
            $id = isset($request[1]) ? intval($request[1]) : null;
            if ($id) {
                $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                echo json_encode($result->fetch_assoc());
            } else {
                $result = $conn->query("SELECT * FROM tasks");
                echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $text = $data['text'];
            $stmt = $conn->prepare("INSERT INTO tasks (text, completed) VALUES (?, ?)");
            $completed = 0;
            $stmt->bind_param("si", $text, $completed);
            $stmt->execute();
            echo json_encode(["id" => $conn->insert_id, "text" => $text, "completed" => $completed]);
            break;

        case 'PUT':
            $id = intval($request[1]);
            $data = json_decode(file_get_contents("php://input"), true);
            $text = $data['text'];
            $completed = $data['completed'];
            $stmt = $conn->prepare("UPDATE tasks SET text = ?, completed = ? WHERE id = ?");
            $stmt->bind_param("sii", $text, $completed, $id);
            $stmt->execute();
            echo json_encode(["id" => $id, "text" => $text, "completed" => $completed]);
            break;

        case 'DELETE':
            $id = intval($request[1]);
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            echo json_encode(["id" => $id]);
            break;

        default:
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
            break;
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint not found"]);
}

$conn->close();
?>
