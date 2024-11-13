<?php
namespace Models;

use Traits\DiscountTrait;

class AnalogWatch extends Watch {
    use DiscountTrait;

    private $material;

    public function __construct($brand, $price, $material) {
        parent::__construct($brand, $price);
        $this->material = $material;
    }

    public function getDescription() {
        return "Brand: {$this->brand}, Material: {$this->material}";
    }

    public function __toString() {
        return "Analog Watch: " . $this->getDescription() . ", Price: $" . $this->price;
    }
}
?>