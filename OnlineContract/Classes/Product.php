<?php

namespace OnlineContract\Classes;

/**
 * Класс для товара
 */
class Product
{
    private string $sku;
    private float $price;
    /**
     * Конструктор
     *
     * @param string $sku
     * @param float $price
     */
    function __construct(string $sku, float $price)
    {
        $this->sku = $sku;
        $this->price = $price;
    }

    public function getSku(): string
    {
        return $this->sku;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
}
