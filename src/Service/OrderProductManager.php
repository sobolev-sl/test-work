<?php


namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OrderProductManager
 * @package App\Service
 */
class OrderProductManager
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
     * @param Order $order
     * @param array $products
     * @return float|int
     * @throws \Exception
     */
    public function create(Order $order, array $products)
    {
        $total = 0;

        foreach($products as $product)
        {
            $oProduct = $this->entityManager
                ->getRepository(Product::class)
                ->find($product['id']);

            if(!$oProduct)
                throw new \Exception("Product: {$product['id']} - not found");

            if(!$this->checkProduct($oProduct, $product['count']))
                throw new \Exception("Error update/create order {$order->getId()}, \ Error available count product id {$oProduct->getId()}");

            $orderProduct = new OrderProduct();
            $orderProduct->setOrder($order);
            $orderProduct->setProduct($oProduct);
            $orderProduct->setCount($product['count']);

            $this->entityManager->persist($orderProduct);
            $this->entityManager->flush();

            $total += $oProduct->getPrice() * $product['count'];
        }

        return $total;
    }

    /**
     * @param Order $order
     * @param array $basketProducts
     * @return float
     * @throws \Exception
     */
    public function update(Order $order, array $basketProducts): float
    {
        $total = 0;

        // Create array like: ['id' => 'count']
        $basketProductItems = array_column($basketProducts, 'count', 'id');

        // Update DB product basket
        foreach($order->getProduct() as $orderProduct)
        {
            $oProduct = $orderProduct->getProduct();

            // Delete removed product from basket
            if(!isset($basketProductItems[$oProduct->getId()]))
            {
                $order->removeProduct($orderProduct);
                continue;
            }

            // Update count
            if(!$this->checkProduct($oProduct, $basketProductItems[$oProduct->getId()]))
                throw new \Exception("Error update order {$order->getId()}, \
                                        Error available count product id {$oProduct->getId()}");

            $orderProduct->setCount($basketProductItems[$oProduct->getId()]);
            $this->entityManager->persist($orderProduct);
            $this->entityManager->flush();

            // Calculate total
            $total += $oProduct->getPrice() * $basketProductItems[$oProduct->getId()];

            // Remove calculated product from basket
            unset($basketProductItems[$oProduct->getId()]);
        }

        // Adding new product
        foreach ($basketProductItems as $basketProductId => $basketProductCount) {

            $product = $this->entityManager
                ->getRepository(Product::class)
                ->find($basketProductId);

            if(!$product)
                throw new \Exception('Error product id: ' . $basketProductId);

            if(!$this->checkProduct($product, $basketProductCount))
                throw new \Exception('Error available count product id: ' . $basketProductId);

            $newOrderProduct = new OrderProduct();
            $newOrderProduct->setCount($basketProductCount);
            $newOrderProduct->setProduct($product);
            $newOrderProduct->setOrder($order);
            $this->entityManager->persist($newOrderProduct);
            $this->entityManager->flush();

            $total += $product->getPrice() * $basketProductCount;
        }

        //
        return $total;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function checkOrderProduct(Order $order): bool
    {
        foreach($order->getProduct() as $orderProduct) {
            $oProduct = $orderProduct->getProduct();
            // Update count
            if (!$this->checkProduct($oProduct, $orderProduct->getCount()))
                return false;
        }
        return true;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function updateProductAvailableCount(Order $order): bool
    {
        foreach($order->getProduct() as $orderProduct) {
            $oProduct = $orderProduct->getProduct();
            $oProduct->setCount($orderProduct->getCount());
            $this->entityManager->persist($oProduct);
            $this->entityManager->flush();
        }

        return true;
    }

    /**
     * @param Product $product
     * @param int $count
     * @return bool
     */
    private function checkProduct(Product $product, int $count): bool
    {
        return $product->getCount() > $count;
    }

    /**
     * @param Product $product
     * @return int
     */
    private function getAvailableCountProduct(Product $product): int
    {
        return $product->getCount();
    }
}