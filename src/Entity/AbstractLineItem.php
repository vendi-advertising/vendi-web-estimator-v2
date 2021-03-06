<?php

namespace App\Entity;

use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\UuidAsIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

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
     * @Groups({"estimate_read"})
     */
    protected $label;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="lineItems")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $section;

    /**
     * @Groups({"estimate_read"})
     * @SerializedName("type")
     */
    public function getLineItemType(): string
    {
        return basename(static::class);
    }

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