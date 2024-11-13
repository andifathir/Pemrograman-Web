<?php

require_once __DIR__ . "/Traits/DiscountTrait.php";
require_once __DIR__ . "/Models/Watch.php";
require_once __DIR__ . "/Models/DigitalWatch.php";
require_once __DIR__ . "/Models/AnalogWatch.php";
require_once __DIR__ . "/Controllers/WatchController.php";

use Controllers\WatchController;

// Membuat objek controller
$watchController = new WatchController();

// Membuat objek jam tangan
$digitalWatch = $watchController->createDigitalWatch("Casio", 100, "Bluetooth, LED Display");
$analogWatch = $watchController->createAnalogWatch("Seiko", 150, "Leather Strap");

// Menerapkan diskon
echo $digitalWatch . "\n";
echo "After discount: $" . $digitalWatch->applyDiscount(10) . "\n\n"; // Diskon 10%

echo $analogWatch . "\n";
echo "After discount: $" . $analogWatch->applyDiscount(15) . "\n"; // Diskon 15%
?>