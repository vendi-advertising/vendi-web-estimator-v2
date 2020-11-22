<?php

namespace App\Entity;

use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\UuidAsIdTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"hour-range" = "HourRangeLineItem", "fixed-cost" = "FixedCostLineItem"})
 */
abstract class AbstractLineItem
{
    use CreatedByTrait;
    use UuidAsIdTrait;
    use SortableTrait;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $label;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="lineItems")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $section;

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }
}