<?php

namespace OnlineContract\Classes;

use OnlineContract\Classes\Interfaces\DiscountProcessorInterface;

class Cart
{
    private DiscountProcessorInterface $discountProcessor;
    private $products = [];
    function __construct(DiscountProcessorInterface $discountProcessor)
    {
        $this->discountProcessor = $discountProcessor;
    }


    /**
     * Сумма все продуктов
     *
     * @return float
     */
    public function getTotal(): float
    {
        $summ = array_reduce($this->products, function (float $summ, Product $product) {
            return $summ += $product->getPrice();
        }, 0);
        $discount = $this->discountProcessor->getDiscount($this->products);
        return $summ - $discount;
    }
    /**
     * Добавить список продуктов в корзину
     *
     * @param [type] $products
     * @return void
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }
}
