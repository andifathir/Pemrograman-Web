<?php

header("Content-Type: application/json");

// Database connection
$host = 'localhost';
$dbname = 'task_manager';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Handle API requests
switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM tasks");
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['email'], $data['tasks'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO tasks (name, email, tasks, completed, created_at) VALUES (?, ?, ?, ?, ?)");
        $completed = $data['completed'] ?? 0;
        $created_at = date('Y-m-d H:i:s');
        $stmt->bind_param("sssds", $data['name'], $data['email'], $data['tasks'], $completed, $created_at);
        $stmt->execute();
        echo json_encode(["message" => "Task added successfully"]);
        $stmt->close();
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['id'], $data['tasks'], $data['completed'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE tasks SET tasks = ?, completed = ? WHERE id = ?");
        $stmt->bind_param("sdi", $data['tasks'], $data['completed'], $data['id']);
        $stmt->execute();
        echo json_encode(["message" => "Task updated successfully"]);
        $stmt->close();
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['id'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        echo json_encode(["message" => "Task deleted successfully"]);
        $stmt->close();
        break;

    default:
        echo json_encode(["error" => "Unsupported HTTP method"]);
}

$conn->close();

?>

<?php

header("Content-Type: application/json");

// Database connection
$host = 'localhost';
$dbname = 'task_manager';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Handle API requests
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $task = $result->fetch_assoc();
            echo json_encode($task ? $task : ["error" => "Task not found"]);
            $stmt->close();
        } else {
            $result = $conn->query("SELECT * FROM tasks");
            $tasks = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($tasks);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['email'], $data['tasks'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        $stmt = $conn->prepare("INSERT INTO tasks (name, email, tasks, completed, created_at) VALUES (?, ?, ?, ?, ?)");
        $completed = $data['completed'] ?? 0;
        $created_at = date('Y-m-d H:i:s');
        $stmt->bind_param("sssds", $data['name'], $data['email'], $data['tasks'], $completed, $created_at);
        $stmt->execute();
        echo json_encode(["message" => "Task added successfully"]);
        $stmt->close();
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['id'], $data['tasks'], $data['completed'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        $stmt = $conn->prepare("UPDATE tasks SET tasks = ?, completed = ? WHERE id = ?");
        $stmt->bind_param("sdi", $data['tasks'], $data['completed'], $data['id']);
        $stmt->execute();
        echo json_encode(["message" => "Task updated successfully"]);
        $stmt->close();
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['id'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        echo json_encode(["message" => "Task deleted successfully"]);
        $stmt->close();
        break;

    default:
        echo json_encode(["error" => "Unsupported HTTP method"]);
}

$conn->close();

?>
