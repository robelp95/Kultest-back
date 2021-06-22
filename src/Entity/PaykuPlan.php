<?php

namespace App\Entity;

use App\Repository\PaykuPlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaykuPlanRepository::class)
 */
class PaykuPlan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paykuId;

    /**
     * @ORM\OneToOne(targetEntity=Suscription::class, mappedBy="plan", cascade={"persist", "remove"})
     */
    private $suscription;

    /**
     * @ORM\OneToMany(targetEntity=Suscription::class, mappedBy="plan")
     */
    private $suscriptions;

    public function __construct()
    {
        $this->suscriptions = new ArrayCollection();
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

    public function getPaykuId(): ?string
    {
        return $this->paykuId;
    }

    public function setPaykuId(string $paykuId): self
    {
        $this->paykuId = $paykuId;

        return $this;
    }

    public function getSuscription(): ?Suscription
    {
        return $this->suscription;
    }

    public function setSuscription(Suscription $suscription): self
    {
        // set the owning side of the relation if necessary
        if ($suscription->getPlan() !== $this) {
            $suscription->setPlan($this);
        }

        $this->suscription = $suscription;

        return $this;
    }

    /**
     * @return Collection|Suscription[]
     */
    public function getSuscriptions(): Collection
    {
        return $this->suscriptions;
    }

    public function addSuscription(Suscription $suscription): self
    {
        if (!$this->suscriptions->contains($suscription)) {
            $this->suscriptions[] = $suscription;
            $suscription->setPlan($this);
        }

        return $this;
    }

    public function removeSuscription(Suscription $suscription): self
    {
        if ($this->suscriptions->removeElement($suscription)) {
            // set the owning side to null (unless already changed)
            if ($suscription->getPlan() === $this) {
                $suscription->setPlan(null);
            }
        }

        return $this;
    }
}
