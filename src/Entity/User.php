<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $brandName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $opening;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $paymentInstructions;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $Address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity=Coin::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userCoin;

    /**
     * @ORM\ManyToOne(targetEntity=OrderVia::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderVia;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank
     */
    private $open;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;

    /**
     * @ORM\Column(type="datetime", nullable=false, options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", options={"default" : "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=Menu::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Menu;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paykuId;

    /**
     * @ORM\OneToMany(targetEntity=Suscription::class, mappedBy="user")
     */
    private $suscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     */
    private $minDelivery;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     */
    private $deliveryCharge;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $orders;

    public function __construct()
    {
        $this->suscription = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    //TODO add empty orders
    public static function create($address,
                                  $brandName,
                                  $category,
                                  $orderVia,
                                  $coin,
                                  $deliveryCharge,
                                  $description,
                                  $email,
                                  $name,
                                  $minDelivery,
                                  $open,
                                  $opening,
                                  $paymentInstructions,
                                  $phoneNumber,
                                  $username,
                                  $menu,
                                  $filename)
    {
        $user =  new self();
        $user->setAddress($address);
        $user->setBrandName($brandName);
        $user->setOrderVia($orderVia);
        $user->setCategory($category);
        $user->setUserCoin($coin);
        $user->setDeliveryCharge($deliveryCharge);
        $user->setDescription($description);
        $user->setEmail($email);
        $user->setName($name);
        $user->setMinDelivery($minDelivery);
        $user->setOpen($open);
        $user->setOpening($opening);
        $user->setPaymentInstructions($paymentInstructions);
        $user->setPhoneNumber($phoneNumber);
        $user->setUsername($username);
        $user->setMenu($menu);
        $user->setImage($filename);
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());
        return $user;

    }

    public function update(
        $address,
        $brandName,
        $category,
        $deliveryCharge,
        $description,
        $name,
        $minDelivery,
        $open,
        $opening,
        $paymentInstructions,
        $phoneNumber,
        $filename){

        $this->setAddress($address);
        $this->setBrandName($brandName);
        $this->setCategory($category);
        $this->setDeliveryCharge($deliveryCharge);
        $this->setDescription($description);
        $this->setImage($filename);
        $this->setMinDelivery($minDelivery);
        $this->setName($name);
        $this->setOpen($open);
        $this->setOpening($opening);
        $this->setPaymentInstructions($paymentInstructions);
        $this->setPhoneNumber($phoneNumber);
        $this->setUpdatedAt(new DateTime());
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrandName(): ?string
    {
        return $this->brandName;
    }

    public function setBrandName(string $brandName): self
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function getOpening(): ?string
    {
        return $this->opening;
    }

    public function setOpening(string $opening): self
    {
        $this->opening = $opening;

        return $this;
    }

    public function getPaymentInstructions(): ?string
    {
        return $this->paymentInstructions;
    }

    public function setPaymentInstructions(string $paymentInstructions): self
    {
        $this->paymentInstructions = $paymentInstructions;

        return $this;
    }


    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserCoin(): ?Coin
    {
        return $this->userCoin;
    }

    public function setUserCoin(?Coin $userCoin): self
    {
        $this->userCoin = $userCoin;

        return $this;
    }

    public function getOrderVia(): ?OrderVia
    {
        return $this->orderVia;
    }

    public function setOrderVia(?OrderVia $orderVia): self
    {
        $this->orderVia = $orderVia;

        return $this;
    }

    public function getOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self
    {
        $this->Category = $Category;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->Menu;
    }

    public function setMenu(Menu $Menu): self
    {
        $this->Menu = $Menu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    /**
     * @return Collection|Suscription[]
     */
    public function getSuscription(): Collection
    {
        return $this->suscription;
    }

    /**
     * @return ArrayCollection|Suscription[]
     */
    public function getActiveSuscription(){
        return $this->getSuscription()->filter(
            function ($sus){
                return $sus->getStatus();
            }
        );
    }

    public function getStatus(){
        return count($this->suscription->filter(function (Suscription $sus){
            return !empty($sus->getStatus());
        })) > 0;
    }

    public function addSuscription(Suscription $suscription): self
    {
        if (!$this->suscription->contains($suscription)) {
            $this->suscription[] = $suscription;
            $suscription->setUser($this);
        }

        return $this;
    }

    public function removeSuscription(Suscription $suscription): self
    {
        if ($this->suscription->removeElement($suscription)) {
            // set the owning side to null (unless already changed)
            if ($suscription->getUser() === $this) {
                $suscription->setUser(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getMinDelivery(): ?int
    {
        return $this->minDelivery;
    }

    public function setMinDelivery(int $minDelivery): self
    {
        $this->minDelivery = $minDelivery;

        return $this;
    }

    public function getDeliveryCharge(): ?int
    {
        return $this->deliveryCharge;
    }

    public function setDeliveryCharge(int $deliveryCharge): self
    {
        $this->deliveryCharge = $deliveryCharge;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }
}
