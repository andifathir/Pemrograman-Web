<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "belajar";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection error: " . $conn->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
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
        // if (!isset($data['name'], $data['email'], $data['tasks'])) {
        //     echo json_encode(["error" => "Missing required fields"]);
        //     exit;
        // }
        $stmt = $conn->prepare("INSERT INTO tasks (name, email, tasks, completed) VALUES (?, ?, ?, ?)");
        $completed = isset($data['completed']) ? intval($data['completed']) : 0;
        $stmt->bind_param("sssi", $data['name'], $data['email'], $data['tasks'], $completed);
        $stmt->execute();
        echo json_encode(["message" => "Task added successfully"]);
        $stmt->close();
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        // if (!isset($data['id'], $data['tasks'], $data['completed'])) {
        //     echo json_encode(["error" => "Missing required fields"]);
        //     exit;
        // }
        $stmt = $conn->prepare("UPDATE tasks SET tasks = ?, completed = ? WHERE id = ?");
        $stmt->bind_param("sii", $data['tasks'], $data['completed'], $data['id']);
        $stmt->execute();
        echo json_encode(["message" => "Task updated successfully"]);
        $stmt->close();
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        // if (!isset($data['id'])) {
        //     echo json_encode(["error" => "Missing required fields"]);
        //     exit;
        // }
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        echo json_encode(["message" => "Task deleted successfully"]);
        $stmt->close();
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}

$conn->close();
?>
