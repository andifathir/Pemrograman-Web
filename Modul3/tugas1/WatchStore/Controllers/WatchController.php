<?php
namespace Controllers;

use Models\DigitalWatch;
use Models\AnalogWatch;

class WatchController {
    public function createDigitalWatch($brand, $price, $features) {
        return new DigitalWatch($brand, $price, $features);
    }

    public function createAnalogWatch($brand, $price, $material) {
        return new AnalogWatch($brand, $price, $material);
    }
}
