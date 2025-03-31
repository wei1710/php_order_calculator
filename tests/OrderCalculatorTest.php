<?php

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OrderCalculatorTest extends TestCase
{
  protected function setUp(): void
  {
    require_once 'classes/OrderCalculator.php';
  }

  /**
   * Positive testing
   */

  #[DataProvider('provideOrderPasses')]
  public function testOrderPasses(float $unitPrice, int $quantity, float $taxRate, float $expected): void
  {
    $order = new OrderCalculator($unitPrice, $quantity, $taxRate);

    $this->assertEquals($expected, $order->calculateFinalPrice());
  }
  public static function provideOrderPasses(): array
  {
    return [
      [30, 1, 0.2, 36],           // Only one product. No discount
      [30, 5, 0, 150],            // No tax rate
      [30, 5, 0.2, 180],          // No discount
      [30, 9, 0.2, 324],          // Max. no. products with no discount
      [30, 10, 0.2, 324],         // Min. no. products with 10% discount
      [30, 15, 0.2, 486],         // 10% discount
      [30, 19, 0.2, 615.6],       // Max. no. products with 10% discount
      [30, 20, 0.2, 612],         // Min. no. products with 15% discount
      [30, 25, 0.2, 765],         // 15% discount
      [30, 524, 0.2, 16034.4],    // Hundreds of products. 15% discount
      [30, 6482, 0.2, 198349.2],  // Thousands of products. 15% discount
    ];
  }

  /**
   * Negative testing
   */

  #[DataProvider('provideOrderFails')]
  public function testOrderFails(float $unitPrice, int $quantity, float $taxRate, float $expected): void
  {
    $order = new OrderCalculator($unitPrice, $quantity, $taxRate);

    $this->assertEquals($expected, $order->calculateFinalPrice());
  }
  public static function provideOrderFails(): array
  {
    return [
      [0, 10, 0.2, 0],            // Price is 0
      [30, 0, 0.2, 0],            // No products
      [0, 0, 0, 0],               // Price, products and tax rate are 0
    ];
  }

  /**
   * Exception testing
   */

  #[DataProvider('provideExceptions')]
  public function testOrderRaisesException(float $unitPrice, int $quantity, float $taxRate): void
  {
    $this->expectException(InvalidArgumentException::class);
    $order = new OrderCalculator($unitPrice, $quantity, $taxRate);
  }
  public static function provideExceptions(): array
  {
    return [
      [-10, 8, 0.2],
      [10, -8, 0.2],
      [10, 8, -0.2]
    ];
  }
}
