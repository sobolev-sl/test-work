<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @package App\Entity
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="order", orphanRemoval=true)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_purchased;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * @param \DateTimeInterface|null $datetime
     * @return $this
     * @throws \Exception
     */
    public function setDatetime(?\DateTimeInterface $datetime = null): self
    {
        $this->datetime = $datetime?: new \DateTime('now', new \DateTimeZone('Europe/Moscow'));;

        return $this;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    /**
     * @param OrderProduct $product
     * @return $this
     */
    public function addProduct(OrderProduct $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setOrder($this);
        }

        return $this;
    }

    /**
     * @param OrderProduct $product
     * @return $this
     */
    public function removeProduct(OrderProduct $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrder() === $this) {
                $product->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsPurchased(): ?bool
    {
        return $this->is_purchased;
    }

    /**
     * @param bool $is_purchased
     * @return $this
     */
    public function setIsPurchased(bool $is_purchased): self
    {
        $this->is_purchased = $is_purchased;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return $this
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}
