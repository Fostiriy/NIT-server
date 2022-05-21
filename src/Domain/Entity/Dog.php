<?php

namespace Domain\Entity;

class Dog
{
    private $name;
    private $weight;

    /**
     * @param $name
     * @param $weight
     */
    public function __construct($name, $weight)
    {
        $this->name = $name;
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }


}