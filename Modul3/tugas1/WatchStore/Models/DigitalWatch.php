<?php
namespace Models;

use Traits\DiscountTrait;

class DigitalWatch extends Watch {
    use DiscountTrait;

    private $features;

    public function __construct($brand, $price, $features) {
        parent::__construct($brand, $price);
        $this->features = $features;
    }

    public function getDescription() {
        return "Brand: {$this->brand}, Features: {$this->features}";
    }

    public function __toString() {
        return "Digital Watch: " . $this->getDescription() . ", Price: $" . $this->price;
    }
}
?>