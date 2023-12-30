<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $isNewTripRequest = null;

    #[ORM\Column]
    private ?bool $isNewMessage = null;

    #[ORM\Column]
    private ?bool $isTripRequestStatusChange = null;

    #[ORM\Column]
    private ?bool $isFriendNewTrip = null;

    #[ORM\OneToOne(inversedBy: 'setting', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $member = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsNewTripRequest(): ?bool
    {
        return $this->isNewTripRequest;
    }

    public function setIsNewTripRequest(bool $isNewTripRequest): static
    {
        $this->isNewTripRequest = $isNewTripRequest;

        return $this;
    }

    public function isIsNewMessage(): ?bool
    {
        return $this->isNewMessage;
    }

    public function setIsNewMessage(bool $isNewMessage): static
    {
        $this->isNewMessage = $isNewMessage;

        return $this;
    }

    public function isIsTripRequestStatusChange(): ?bool
    {
        return $this->isTripRequestStatusChange;
    }

    public function setIsTripRequestStatusChange(bool $isTripRequestStatusChange): static
    {
        $this->isTripRequestStatusChange = $isTripRequestStatusChange;

        return $this;
    }

    public function isIsFriendNewTrip(): ?bool
    {
        return $this->isFriendNewTrip;
    }

    public function setIsFriendNewTrip(bool $isFriendNewTrip): static
    {
        $this->isFriendNewTrip = $isFriendNewTrip;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(User $member): static
    {
        $this->member = $member;

        return $this;
    }
}
