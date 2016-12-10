<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanetResource
 *
 * @ORM\Table(name="planet_resources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlanetResourceRepository")
 */
class PlanetResource
{
    /**
     * @var int
     *
     * @ORM\Column(name="planet_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $planet_id;

    /**
     * @var int
     *
     * @ORM\Column(name="resource_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $resource_id;

    /**
     * PlanetResource constructor.
     * @param int $planet_id
     * @param int $resource_id
     */
    public function __construct($planet_id, $resource_id)
    {
        $this->planet_id = $planet_id;
        $this->resource_id = $resource_id;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return PlanetResource
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    // relations

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Planet", inversedBy="planetResources")
     * @ORM\JoinColumn(name="planet_id", referencedColumnName="id", nullable=false)
     */
    private $planet;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Resource", inversedBy="planetResources")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id", nullable=false)
     */
    private $resource;

    /**
     * @return mixed
     */
    public function getPlanet()
    {
        return $this->planet;
    }

    /**
     * @param mixed $planet
     */
    public function setPlanet($planet)
    {
        $this->planet = $planet;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }
}

