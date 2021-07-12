<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Service\OrderManager;
use App\Service\OrderProductManager;
use App\Traits\OrderRequestData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller
 */
class OrderController extends AbstractController
{
    use OrderRequestData;

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @param OrderProductManager $orderProductManager
     * @param Order $order
     * @return Response
     */
    public function getOrder(Request $request,
                           OrderManager $orderManager,
                           OrderProductManager $orderProductManager,
                           Order $order): Response
    {
        return $this->json($this->successRequest($order,
            "Get order info: {$order->getId()}"
        ));
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @param OrderProductManager $orderProductManager
     * @return Response
     */
    public function create(Request $request,
                           OrderManager $orderManager,
                           OrderProductManager $orderProductManager): Response
    {
        try {
            $requestData = $request->getContent();
            $requestData = json_decode($requestData, true);

            if(!isset($requestData['user']))
                throw new \Exception('User not found', 404);

            if(!isset($requestData['products']))
                throw new \Exception('Products not found', 404);

            $order = $orderManager->createOrder(
                $orderProductManager,
                $requestData['user'],
                $requestData['products']);
        }
        catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage(),
                'code' => 404,
                'data' => []
            ]);
        }

        return $this->json($this->successRequest($order,
            "Temporary order is created: {$order->getId()}"
        ));
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @param OrderProductManager $orderProductManager
     * @param Order $order
     * @return Response
     */
    public function update(Request $request,
                           OrderManager $orderManager,
                           OrderProductManager $orderProductManager,
                           Order $order): Response
    {
        $requestData = $request->getContent();
        $requestData = json_decode($requestData, true);

        // Error for update purchased order
        if($order->getIsPurchased())
            return $this->json(
                $this->errorRequest($order, "Unable update purchased order: {$order->getId()}")
            );

        // If empty order basket
        if(!isset($requestData['products']) || empty($requestData['products']))
            return $this->json($this->successRequest($order,
                "Nothing update for order: {$order->getId()}"
            ));

        try {
            $total = $orderManager->updateOrder($orderProductManager, $order, $requestData['products']);

            if (!$total)
                return $this->json($this->successRequest($order,
                    "Order: {$order->getId()} has empty order basket"
                ));
        }
        catch(\Exception $e) {
            return $this->json(
                $this->errorRequest($order, $e->getMessage())
            );
        }

        return $this->json($this->successRequest($order,
            "Temporary order is updated: {$order->getId()}"
        ));
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @param OrderProductManager $orderProductManager
     * @param Order $order
     * @return Response
     */
    public function purchase(Request $request,
                            OrderManager $orderManager,
                            OrderProductManager $orderProductManager,
                            Order $order): Response
    {
        // Check available product in storage
        if($orderManager->purchaseOrder($orderProductManager, $order)) {
            $sendData = $this->successRequest($order,
                "Order is purchased: {$order->getId()}"
            );
        }
        else
            $sendData = $this->successRequest($order,
                "Error purchased order: {$order->getId()}, error available product count"
            );

        return $this->json($sendData);
    }
}
