<?php

namespace App\Entity;

use App\Repository\HourRangeLineItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=HourRangeLineItemRepository::class)
 */
class HourRangeLineItem extends AbstractLineItem
{
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"estimate_read"})
     */
    private $hoursLow;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"estimate_read"})
     */
    private $hoursHigh;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @Groups({"estimate_read"})
     */
    private $staffQuantity;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"estimate_read"})
     */
    private $rate;

    public function __construct(string $label, float $hoursLow, float $hoursHigh, float $staffQuantity, int $sortOrder, ?Section $section, ?float $rate)
    {
        $this->label = $label;
        $this->hoursLow = $hoursLow;
        $this->hoursHigh = $hoursHigh;
        $this->staffQuantity = $staffQuantity;
        $this->rate = $rate;
        $this->sortOrder = $sortOrder;
        $this->section = $section;
    }

    public function getHoursLow(): ?string
    {
        return $this->hoursLow;
    }

    public function setHoursLow(string $hoursLow): self
    {
        $this->hoursLow = $hoursLow;

        return $this;
    }

    public function getHoursHigh(): ?string
    {
        return $this->hoursHigh;
    }

    public function setHoursHigh(string $hoursHigh): self
    {
        $this->hoursHigh = $hoursHigh;

        return $this;
    }

    public function getStaffQuantity(): ?string
    {
        return $this->staffQuantity;
    }

    public function setStaffQuantity(string $staffQuantity): self
    {
        $this->staffQuantity = $staffQuantity;

        return $this;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(?string $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
