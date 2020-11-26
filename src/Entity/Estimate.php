<?php

namespace App\Entity;

use App\Entity\Traits\AppTimestampableEntity;
use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\UuidAsIdTrait;
use App\Repository\EstimateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EstimateRepository::class)
 * @Gedmo\Loggable
 */
class Estimate
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use AppTimestampableEntity;
    use CreatedByTrait;
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     * @Groups({"estimate_read"})
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     * @Groups({"estimate_read"})
     */
    private ?string $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"estimate_read"})
     */
    private float $defaultRate = 0;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="estimate")
     * @Groups({"estimate_read"})
     */
    private $sections;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
        $this->sections = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDefaultRate(): ?string
    {
        return $this->defaultRate;
    }

    public function setDefaultRate(string $defaultRate): self
    {
        $this->defaultRate = $defaultRate;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setEstimate($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getEstimate() === $this) {
                $section->setEstimate(null);
            }
        }

        return $this;
    }
}
