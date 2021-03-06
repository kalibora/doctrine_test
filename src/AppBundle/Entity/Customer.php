<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CustomerRepository")
 * @ORM\Table(name="customers")
 */
class Customer
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Order", mappedBy="customer")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add order
     *
     * @param Order $order
     *
     * @return Customer
     */
    public function addOrder(Order $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param Order $order
     */
    public function removeOrder(Order $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    public function getCuillenMonths()
    {
        $months = [];

        foreach ($this->orders as $order) {
            if ($order->isCuillen()) {
                $months[] = $order->getDate()->format('Y-m');
            }
        }

        return array_unique($months);
    }

    public function getOrdersByDate(\DateTimeInterface $date)
    {
        $orders = [];

        foreach ($this->orders as $order) {
            if ($order->getDate()->format('Ymd') === $date->format('Ymd')) {
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getOrdersByDateWithCriteria(\DateTimeInterface $date)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('date', $date))
        ;

        return $this->orders->matching($criteria);
    }
}
