<?php

namespace OnlineContract\Classes\Interfaces;


interface DiscountProcessorInterface
{
    /**
     * Вернуть сумму скидки
     *
     * @param OnlineContract\Classes\Product[] $products массив продуктов
     * @return float
     */
    public function getDiscount(array $products): float;
    /**
     * Задать конфиг скидок
     *
     * @param array $rules
     * @return void
     */
    public function setRules(array $rules): void;
}
