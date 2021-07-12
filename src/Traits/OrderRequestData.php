<?php


namespace App\Traits;

use App\Entity\Order;

/**
 * Trait OrderRequestData
 * @package App\Traits
 */
trait OrderRequestData
{
    /**
     * @param Order $order
     * @param int|null $total
     * @return array
     */
    public function orderData(Order $order, int $total = null): array
    {
        $products = [];

        foreach($order->getProduct() as $orderProduct)
        {
            $products[] = [
                'sku' => $orderProduct->getProduct()->getSku(),
                'count' => $orderProduct->getCount()
            ];
        }
        return [
            'order'  => $order->getId(),
            'status' => $order->getIsPurchased(),
            'total'  => $total? $total : $order->getTotal(),
            'products' => $products
        ];
    }

    /**
     * @param Order $order
     * @param string $message
     * @return array
     */
    public function successRequest(Order $order, string $message = ""): array
    {
        return [
            'message' => $message,
            'code' => 200,
            'data' => $this->orderData($order)
        ];
    }

    /**
     * @param Order $order
     * @param string $message
     * @return array
     */
    public function errorRequest(Order $order, string $message = ""): array
    {
        return [
            'message' => $message,
            'code' => 500,
            'data' => $this->orderData($order)
        ];
    }
}