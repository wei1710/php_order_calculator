<?php

use PHPUnit\Framework\TestCase;

require_once 'classes/OrderCalculator.php';

class OrderCalculatorTest extends TestCase
{

  public function testCalculateFinalPriceNoDiscount(): void
  {
    $calc = new OrderCalculator(10.00, 5); // no discount
    $expected = round(10.00 * 5 * 1.2, 2); // total * tax
    $this->assertEquals($expected, $calc->calculateFinalPrice());
  }

  public function testCalculateFinalPriceWith10PercentDiscount(): void
  {
    $calc = new OrderCalculator(10.00, 10); // 10% discount
    $expected = round((10.00 * 10 * 0.9) * 1.2, 2);
    $this->assertEquals($expected, $calc->calculateFinalPrice());
  }

  public function testCalculateFinalPriceWith15PercentDiscount(): void
  {
    $calc = new OrderCalculator(10.00, 20); // 15% discount
    $expected = round((10.00 * 20 * 0.85) * 1.2, 2);
    $this->assertEquals($expected, $calc->calculateFinalPrice());
  }

  public function testThrowsExceptionForNegativePrice(): void
  {
    $this->expectException(InvalidArgumentException::class);
    new OrderCalculator(-1, 5);
  }

  public function testThrowsExceptionForNegativeQuantity(): void
  {
    $this->expectException(InvalidArgumentException::class);
    new OrderCalculator(10, -5);
  }

  public function testThrowsExceptionForNegativeTax(): void
  {
    $this->expectException(InvalidArgumentException::class);
    new OrderCalculator(10, 5, -0.1);
  }
}
