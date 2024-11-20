<?php

namespace App\Models;

include "Config/DatabaseConfig.php";

use app\Config\DatabaseConfig;
use mysqli;

class Product extends DatabaseConfig
{
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database_name, $this->port);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function findAll()
    {
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        return $data;
    }

    public function create($data)
    {
        $sql = "INSERT INTO products (name, brand, description, price, quantity_in_stock, image_url) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssdis",
            $data['name'],
            $data['brand'],
            $data['description'],
            $data['price'],
            $data['quantity_in_stock'],
            $data['image_url']
        );
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return $this->findById($this->conn->insert_id);
        }

        return null;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE products 
                SET name = ?, brand = ?, description = ?, price = ?, quantity_in_stock = ?, image_url = ?, updated_at = NOW() 
                WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssdisi",
            $data['name'],
            $data['brand'],
            $data['description'],
            $data['price'],
            $data['quantity_in_stock'],
            $data['image_url'],
            $id
        );
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
