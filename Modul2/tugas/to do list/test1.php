<?php


$server = "localhost";
$user = "root";
$pass = "";
$database = "test";

$conn = new mysqli($server, $user, $pass, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['add'])) {
        $taskTitle = htmlspecialchars($_POST['title']);
        if (!empty($taskTitle)) {
            # code...
            $stmt = $conn->prepare("INSERT INTO tasks (title, completed) VALUES (?,0)");
            $stmt->bind_param("s", $taskTitle);
            $stmt->execute();
            $stmt->close();
        }
    }elseif(isset($_POST['toggle'])) {
        $taskId = $_POST['id'];
        $stmt = $conn->prepare("UPDATE tasks SET completed = !completed WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $stmt->close();
    }elseif(isset($_POST['delete'])) {
        $taskId = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $stmt->close();
 
    }
}

$task = [];
$tasks = $conn->query("SELECT * FROM tasks");
if ($result) {
    # code...
    $tasks = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>To-Do List</title>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white shadow-md rounded p-4">
        <h1 class="text-2xl font-bold mb-4">To-Do List</h1>
        <form method="POST" class="flex mb-4">
            <input name="title" type="text" placeholder="New Task" class="border border-gray-300 rounded p-2 flex-grow" required>
            <button type="submit" name="add" class="ml-2 bg-blue-500 text-white rounded px-4 py-2">Add</button>
        </form>
        <ul class="list-disc pl-5">
            <?php foreach ($tasks as $task): ?>
                <li class="mb-2 flex justify-between items-center">
                    <span class="<?= $task['completed'] ? 'line-through' : '' ?>">
                        <?= htmlspecialchars($task['title']) ?>
                    </span>
                    <div>
                        <form action="api.php" method="POST" class="inline">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <button type="submit" name="toggle" class="bg-green-500 text-white rounded px-2 py-1">Toggle</button>
                        </form>
                        <form method="POST" class="inline">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <button type="submit" name="delete" class="bg-red-500 text-white rounded px-2 py-1">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>