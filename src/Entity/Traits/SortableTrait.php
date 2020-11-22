<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SortableTrait
{
    /**
     * @ORM\Column(type="integer")
     */
    protected int $sortOrder;

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }
}