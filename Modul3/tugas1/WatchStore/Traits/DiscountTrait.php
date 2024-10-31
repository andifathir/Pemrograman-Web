<?php
namespace Traits;

trait DiscountTrait {
    public function applyDiscount($amount) {
        return $this->price - ($this->price * ($amount / 100));
    }
}
