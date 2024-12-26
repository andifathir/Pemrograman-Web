<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'belajar';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    echo json_encode(["Error" => "Database connection nasdfjalsdjfkald". $conn->connect_error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM tasks");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case 'POST':
        $data = json_decode(file_get_contents("http://input"),true);
        $stmt = $conn->prepare("INSERT INTO ngewe (nasd,asdfjasd) VALUES (?,?)");
        $stmt->bind_param("ss", $data['asdf']);
        $stmt->execute();
        echo json_encode(["Message" => "Task added"]);
        $stmt->close();
        break;

    case 'GET':
        $data = json_decode(file_get_contents("http://input"),true);
        $stmt = $conn->prepare("UPDATE ngewe SET task=? WHERE id=?");
        $stmt->bind_param("ss", $data['asdf']);
        $stmt->execute();
        echo json_encode(["Message" => "Task added"]);
        $stmt->close();
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("http://input"),true);
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
        $stmt->bind_param("ss", $data['asdf']);
        $stmt->execute();
        echo json_encode(["Message" => "Task added"]);
        $stmt->close();
        break;
    
    default:
        # code...
        break;
}

$conn->close();
?>