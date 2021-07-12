<?php

namespace App\Service;

use App\Entity\OrderProduct;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use Exception;

/**
 * Class OrderManager
 * @package App\Service
 */
class OrderManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * OrderManager constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param OrderProductManager $orderProductManager
     * @param $user
     * @param array $products
     * @return Order
     * @throws Exception
     */
    public function createOrder(OrderProductManager $orderProductManager, $user, array $products): Order
    {
        // Find user
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($user);

        if(!$user instanceof User) {
            // Also can create concrete UserException class
            throw new Exception('User not found', 404);
        }

        $userOrders = $user->getOrders();

        foreach($userOrders as $userOrder)
            if(!$userOrder->getIsPurchased())
                throw new Exception("User already have non finished order");

        // Start transaction
        $this->entityManager->beginTransaction();

        $order = new Order();
        $order->setUser($user);
        $order->setDatetime();
        $order->setIsPurchased(false);
        $order->setTotal(0);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // Fill OrderProduct
        $total = $orderProductManager->create($order, $products);
        $order->setTotal($total);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $this->entityManager->commit();
        // End transaction

        return $order;
    }

    /**
     * @param OrderProductManager $orderProductManager
     * @param Order $order
     * @param array $products
     * @return float
     * @throws Exception
     */
    public function updateOrder(OrderProductManager $orderProductManager, Order $order, array $products): float
    {
        $total = $orderProductManager->update($order, $products);
        $order->setTotal($total);

        // Update total
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $total;
    }

    /**
     * @param OrderProductManager $orderProductManager
     * @param Order $order
     * @return bool
     */
    public function purchaseOrder(OrderProductManager $orderProductManager, Order $order): bool
    {
        if($orderProductManager->checkOrderProduct($order))
        {
            $this->entityManager->beginTransaction();

            $orderProductManager->updateProductAvailableCount($order);

            $order->setIsPurchased(true);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return true;
        }

        return false;
    }
}