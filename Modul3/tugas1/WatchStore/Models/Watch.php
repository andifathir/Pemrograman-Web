<?php
namespace Models;

abstract class Watch {
    // Properties
    protected $brand;
    protected $price;

    // Constructor
    public function __construct($brand, $price) {
        $this->brand = $brand;
        $this->price = $price;
    }

    // Abstract method
    abstract public function getDescription();
}
?>