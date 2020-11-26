<?php

namespace App\Entity;

use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\SortableTrait;
use App\Entity\Traits\UuidAsIdTrait;
use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
{
    use CreatedByTrait;
    use UuidAsIdTrait;
    use SortableTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"estimate_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Estimate::class, inversedBy="sections")
     */
    private $estimate;

    /**
     * @ORM\OneToMany(targetEntity=AbstractLineItem::class, mappedBy="section", orphanRemoval=true)
     * @Groups({"estimate_read"})
     */
    private $lineItems;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEstimate(): ?Estimate
    {
        return $this->estimate;
    }

    public function setEstimate(?Estimate $estimate): self
    {
        $this->estimate = $estimate;

        return $this;
    }

    /**
     * @return Collection|AbstractLineItem[]
     */
    public function getLineItems(): Collection
    {
        return $this->lineItems;
    }

    public function addLineItem(AbstractLineItem $lineItem): self
    {
        if (!$this->lineItems->contains($lineItem)) {
            $this->lineItems[] = $lineItem;
            $lineItem->setSection($this);
        }

        return $this;
    }

    public function removeLineItem(AbstractLineItem $lineItem): self
    {
        if ($this->lineItems->removeElement($lineItem)) {
            // set the owning side to null (unless already changed)
            if ($lineItem->getSection() === $this) {
                $lineItem->setSection(null);
            }
        }

        return $this;
    }
}
