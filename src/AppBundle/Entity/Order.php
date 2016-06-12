<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="orders")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="LineItem", mappedBy="order")
     */
    private $lineItems;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTimeInterface $date
     *
     * @return Order
     */
    public function setDate(\DateTimeInterface $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set customer
     *
     * @param Customer $customer
     *
     * @return Order
     */
    public function setCustomer(Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add lineItem
     *
     * @param LineItem $lineItem
     *
     * @return Order
     */
    public function addLineItem(LineItem $lineItem)
    {
        $this->lineItems[] = $lineItem;

        return $this;
    }

    /**
     * Remove lineItem
     *
     * @param LineItem $lineItem
     */
    public function removeLineItem(LineItem $lineItem)
    {
        $this->lineItems->removeElement($lineItem);
    }

    /**
     * Get lineItems
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    public function isCuillen()
    {
        $discountableAmount = 0;

        foreach ($this->lineItems as $lineItem) {
            if ($lineItem->getProduct() === 'Talisker') {
                $discountableAmount += $lineItem->getCost();
            }
        }

        return $discountableAmount >= 5000;
    }
}
