<?php
require_once 'autoload.php';

use OnlineContract\Classes\Cart;
use OnlineContract\Classes\DiscountProcessor;
use OnlineContract\Classes\Product;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    public function testAll()
    {
        /**
         * Конфигурация скидки со всем вариантами.
         * type - тип скидки: simple - скидка за комбинацию, simple_if_select_other - скидка при условии выбора другого товара
         * simple_count - скидка от количества.
         * В первых двух скидка применяется для товаров из sku, в последнем на всю сумму заказа
         */
        $discountConfig = [
            [
                'sku' => ['A', 'B'], 'discount' => 10, 'type' => 'simple',
            ],
            [
                'sku' => ['D', 'E'], 'discount' => 6, 'type' => 'simple',
            ],
            [
                'sku' => ['E', 'F', 'G'], 'discount' => 3, 'type' => 'simple',
            ],
            [
                'sku' => ['A'], 'other' => ['K', 'L', 'M'], 'discount' => 5, 'type' => 'simple_if_select_other',
            ],
            [
                'exclude_sku' => ['A', 'C'], 'type' => 'simple_count', 'discounts' => [
                    3 => 5,
                    4 => 10,
                    5 => 20,
                ],
            ],
        ];

        /**
         * Продукты
         */
        $products = [];
        foreach (range('A', 'M') as $sku) {
            $products[] = new Product($sku, 1000); // цена каждого продукта 1000
        }

        $discountProcessor = new DiscountProcessor();
        $cart = new Cart($discountProcessor);
        $cart->setProducts($products);

        // тестим каждый тип скидки
        // общая сумма товаров 13000

        $discountProcessor->setRules([['sku' => ['A', 'B'], 'discount' => 10, 'type' => 'simple']]); // скидка 200
        $this->assertEquals($cart->getTotal(), 12800);

        $discountProcessor->setRules([['sku' => ['A'], 'other' => ['K', 'L', 'M'], 'discount' => 5, 'type' => 'simple_if_select_other']]); // скидка 50
        $this->assertEquals($cart->getTotal(), 12950);

        $discountProcessor->setRules([['exclude_sku' => ['A', 'C'], 'type' => 'simple_count', 'discounts' => [
            3 => 5,
            4 => 10,
            5 => 20,
        ]]]); // скидка 2600
        $this->assertEquals($cart->getTotal(), 10400);
    }
}
