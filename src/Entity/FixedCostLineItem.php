<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class FixedCostLineItem extends AbstractLineItem
{
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private float $cost;

    public function __construct(string $label, float $cost, int $sortOrder)
    {
        $this->label = $label;
        $this->cost = $cost;
        $this->sortOrder = $sortOrder;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): FixedCostLineItem
    {
        $this->cost = $cost;
        return $this;
    }
}