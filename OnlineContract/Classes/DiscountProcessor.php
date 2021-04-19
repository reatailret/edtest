<?php

namespace OnlineContract\Classes;

use OnlineContract\Classes\Interfaces\DiscountProcessorInterface;

class DiscountProcessor implements DiscountProcessorInterface
{
    protected array $rules = [];
    public function setRules(array $rules): void
    {
        $this->rules = $rules;
    }
    public function getDiscount(array $products): float
    {
        $productCounts = [];
        $productWithAppliedDiscounts = [];
        $totalPrice = 0.0;
        // считаем количество отдельных продуктов, после применения к ним скидки - уменьшаем количество
        foreach ($products as $product) {
            if (!isset($productCounts[$product->getSku()]))  $productCounts[$product->getSku()] = ['count' => 0, 'price' => $product->getPrice()];
            $productCounts[$product->getSku()]['count']++;
            $totalPrice += $productCounts[$product->getSku()]['price'];
        }
        $productCountsFresh = $productCounts; // для подсчёта суммарной скидки
        $totalDiscount = 0.0;
        // парсим и применяем правила
        foreach ($this->rules as $rule) {

            switch ($rule['type']) {
                case 'simple': // для указанных товаров применить суммарно скидку
                    // ищем комбинации продуктов для суммарной скидки
                    $sku_array = $rule['sku'];
                    $searched_array = array_filter($productCounts, function ($v, $k) use ($sku_array) {
                        return $v['count'] > 0 && in_array($k, $sku_array);
                    }, ARRAY_FILTER_USE_BOTH);
                    // если есть нужные комбинации товаров - считаем скидку, уменьшаем остаток
                    if (count($sku_array) == count(array_keys($searched_array))) {
                        $tmpSum = 0;
                        foreach ($sku_array as $sku) {
                            $productCounts[$sku]['count']--;
                            $tmpSum += $productCounts[$sku]['price'];
                        }

                        $totalDiscount += $tmpSum / 100 * $rule['discount'];
                    }
                    break;
                case 'simple_count': // скидка по количеству выбранных 
                    // считаем сколько типов продуктов выбрано, исключая исключенные
                    $sku_array = $rule['exclude_sku'];
                    $searched_array = array_filter($productCountsFresh, function ($v, $k) use ($sku_array) {
                        return !in_array($k, $sku_array);
                    }, ARRAY_FILTER_USE_BOTH);
                    $selectedProductsCount = count(array_keys($searched_array));
                    
                    $discountToApply = 0;
                    foreach ($rule['discounts'] as $count => $discountValue) {
                        if ($count <= $selectedProductsCount) {
                            $discountToApply = $discountValue;
                        }
                    }
                    $totalDiscount += $totalPrice / 100 * $discountToApply;
                    break;
                case 'simple_if_select_other': // если выбран продукт и ещё  какой-нибудь из списка
                    // ищем комбинации продуктов для суммарной скидки
                    $sku_array = $rule['sku'];
                    $sku_array_optional = $rule['other'];

                    $searched_array = array_filter($productCounts, function ($v, $k) use ($sku_array) {
                        return $v['count'] > 0 && in_array($k, $sku_array);
                    }, ARRAY_FILTER_USE_BOTH);
                    $searched_array_optional = array_filter($productCountsFresh, function ($v, $k) use ($sku_array_optional) {
                        return in_array($k, $sku_array_optional);
                    }, ARRAY_FILTER_USE_BOTH);
                    // если есть нужные комбинации товаров - считаем скидку, уменьшаем остаток
                    if (count($sku_array) && count(array_keys($searched_array_optional))) {
                        $tmpSum = 0;
                        foreach ($sku_array as $sku) {
                            $productCounts[$sku]['count']--;
                            $tmpSum += $productCounts[$sku]['price'];
                        }

                        $totalDiscount += $tmpSum / 100 * $rule['discount'];
                    }
                    break;

                default:

                    break;
            }
        }
        return $totalDiscount;
    }
}
