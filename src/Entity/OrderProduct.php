<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderProductRepository::class)
 */
class OrderProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $count;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orderProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     * @return $this
     */
    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return $this
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
