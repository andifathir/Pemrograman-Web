<?php

namespace app\Controller;

include "Traits/ApiResponseFormatter.php";
include "Models/Product.php";

use app\Models\Product;
use app\Traits\ApiResponseFormatter;

class ProductController
{
    use ApiResponseFormatter;

    public function index()
    {
        $productModel = new Product();
        $response = $productModel->findAll();
        return $this->apiResponse(200, "Success", $response);
    }

    public function getById($id)
    {
        $productModel = new Product();
        $response = $productModel->findById($id);
        if (!$response) {
            return $this->apiResponse(404, "Product not found", null);
        }
        return $this->apiResponse(200, "Success", $response);
    }

    public function insert()
    {
        $jsonInput = file_get_contents('php://input');
        $inputData = json_decode($jsonInput, true);

        if (json_last_error()) {
            $error = json_last_error_msg();
            return $this->apiResponse(400, "Invalid input: $error", null);
        }

        $requiredFields = ['name', 'brand', 'description', 'price', 'quantity_in_stock', 'image_url'];
        foreach ($requiredFields as $field) {
            if (!isset($inputData[$field]) || empty($inputData[$field])) {
                return $this->apiResponse(400, "Error: $field is required", null);
            }
        }

        $productModel = new Product();
        $response = $productModel->create($inputData);
        return $this->apiResponse(200, "Product created successfully", $response);
    }

    public function update($id)
    {
        $jsonInput = file_get_contents('php://input');
        $inputData = json_decode($jsonInput, true);

        if (json_last_error()) {
            $error = json_last_error_msg();
            return $this->apiResponse(400, "Invalid JSON format: $error", null);
        }

        $productModel = new Product();
        $result = $productModel->update($id, $inputData);

        if ($result) {
            return $this->apiResponse(200, "Product updated successfully", null);
        } else {
            return $this->apiResponse(404, "Product not found or no changes made", null);
        }
    }

    public function delete($id)
    {
        $productModel = new Product();
        $result = $productModel->delete($id);

        if ($result) {
            return $this->apiResponse(200, "Product deleted successfully", null);
        } else {
            return $this->apiResponse(404, "Product not found", null);
        }
    }
}
