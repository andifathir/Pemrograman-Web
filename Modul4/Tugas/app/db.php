<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "timeless";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mendapatkan ID dari URL jika ada
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;

// Cek jenis request
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($product_id) {
            // Mengambil satu produk berdasarkan ID
            $sql = "SELECT product_id, name, brand, description, price, quantity_in_stock, image_url, created_at, updated_at 
                    FROM products WHERE product_id = $product_id";
        } else {
            // Mengambil semua produk jika ID tidak diberikan
            $sql = "SELECT product_id, name, brand, description, price, quantity_in_stock, image_url, created_at, updated_at 
                    FROM products";
        }
        
        $result = $conn->query($sql);
        $products = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['image_url'] = '/Modul4/Tugas/app/assets/images/' . $row['image_url']; // Adjust the image URL path as necessary
                $products[] = $row;
            }
            echo json_encode($products);
        } else {
            echo json_encode([]);
        }
        break;

    case 'POST':
        // Menambahkan produk baru
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $brand = $data['brand'];
        $description = $data['description'];
        $price = $data['price'];
        $quantity_in_stock = $data['quantity_in_stock'];
        $image_url = $data['image_url'];

        $sql = "INSERT INTO Products (name, brand, description, price, quantity_in_stock, image_url, created_at, updated_at) 
                VALUES ('$name', '$brand', '$description', '$price', '$quantity_in_stock', '$image_url', NOW(), NOW())";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Product added successfully']);
        } else {
            echo json_encode(['message' => 'Error adding product: ' . $conn->error]);
        }
        break;

    case 'PUT':
        // Mengupdate produk berdasarkan ID
        if ($product_id) {
            $data = json_decode(file_get_contents('php://input'), true);
            $name = $data['name'];
            $brand = $data['brand'];
            $description = $data['description'];
            $price = $data['price'];
            $quantity_in_stock = $data['quantity_in_stock'];
            $image_url = $data['image_url'];

            $sql = "UPDATE Products 
                    SET name = '$name', brand = '$brand', description = '$description', 
                        price = '$price', quantity_in_stock = '$quantity_in_stock', 
                        image_url = '$image_url', updated_at = NOW() 
                    WHERE product_id = $product_id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['message' => 'Product updated successfully']);
            } else {
                echo json_encode(['message' => 'Error updating product: ' . $conn->error]);
            }
        } else {
            echo json_encode(['message' => 'Product ID is required to update']);
        }
        break;

    case 'DELETE':
        // Menghapus produk berdasarkan ID
        if ($product_id) {
            $sql = "DELETE FROM Products WHERE product_id = $product_id";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(['message' => 'Product deleted successfully']);
            } else {
                echo json_encode(['message' => 'Error deleting product: ' . $conn->error]);
            }
        } else {
            echo json_encode(['message' => 'Product ID is required to delete']);
        }
        break;

    default:
        echo json_encode(['message' => 'Request method not supported']);
        break;
}

$conn->close();

?>
