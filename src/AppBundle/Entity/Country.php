<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CountryRepository")
 * @ORM\Table(name="countries")
 */
class Country
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
     * @ORM\OneToOne(targetEntity="CapitalCity", mappedBy="country")
     */
    private $capitalCity;

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
     * @return Country
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
     * Set capitalCity
     *
     * @param CapitalCity $capitalCity
     *
     * @return Country
     */
    public function setCapitalCity(CapitalCity $capitalCity = null)
    {
        $this->capitalCity = $capitalCity;

        return $this;
    }

    /**
     * Get capitalCity
     *
     * @return CapitalCity
     */
    public function getCapitalCity()
    {
        return $this->capitalCity;
    }
}
