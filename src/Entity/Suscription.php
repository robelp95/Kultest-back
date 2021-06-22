<?php

namespace App\Entity;

use App\Repository\SuscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SuscriptionRepository::class)
 */
class Suscription
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
    private $paykuId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Suscription")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=PaykuPlan::class, inversedBy="suscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $plan;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserPaykuId(){
        return $this->getUser()->getPaykuId();
    }

        public function getPlan(): ?PaykuPlan
    {
        return $this->plan;
    }

    public function setPlan(?PaykuPlan $plan): self
    {
        $this->plan = $plan;

        return $this;
    }
}
