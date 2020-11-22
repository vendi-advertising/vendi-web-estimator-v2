<?php

namespace App\Entity;

use App\Entity\Traits\CreatedByTrait;
use App\Entity\Traits\UuidAsIdTrait;
use App\Repository\EstimateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

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
    use TimestampableEntity;
    use CreatedByTrait;
    use UuidAsIdTrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private float $defaultRate = 0;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="estimate")
     */
    private $sections;

    public function __construct()
    {
        $this->lineItems = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
