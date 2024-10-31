<?php
// Namespace
namespace WatchStore;

// Trait untuk diskon tambahan
trait DiscountTrait {
    public function applyDiscount($amount) {
        return $this->price - ($this->price * ($amount / 100));
    }
}

// Abstract class untuk jam tangan
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

// Class Turunan untuk jam tangan Digital
class DigitalWatch extends Watch {
    use DiscountTrait;

    // Additional property
    private $features;

    public function __construct($brand, $price, $features) {
        parent::__construct($brand, $price);
        $this->features = $features;
    }

    public function getDescription() {
        return "Brand: {$this->brand}, Features: {$this->features}";
    }

    // Magic method __toString
    public function __toString() {
        return "Digital Watch: " . $this->getDescription() . ", Price: $" . $this->price;
    }
}

// Class Turunan untuk jam tangan Analog
class AnalogWatch extends Watch {
    use DiscountTrait;

    // Additional property
    private $material;

    public function __construct($brand, $price, $material) {
        parent::__construct($brand, $price);
        $this->material = $material;
    }

    public function getDescription() {
        return "Brand: {$this->brand}, Material: {$this->material}";
    }

    // Magic method __toString
    public function __toString() {
        return "Analog Watch: " . $this->getDescription() . ", Price: $" . $this->price;
    }
}

// Menggunakan namespace dan membuat objek
namespace Main;

use WatchStore\DigitalWatch;
use WatchStore\AnalogWatch;

// Membuat objek jam tangan
$digitalWatch = new DigitalWatch("Casio", 100, "Bluetooth, LED Display");
$analogWatch = new AnalogWatch("Seiko", 150, "Leather Strap");

// Menerapkan diskon
echo $digitalWatch . "\n";
echo "After discount: $" . $digitalWatch->applyDiscount(10) . "\n\n"; // Diskon 10%

echo $analogWatch . "\n";
echo "After discount: $" . $analogWatch->applyDiscount(15) . "\n"; // Diskon 15%
